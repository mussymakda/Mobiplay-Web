<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


//stripe listen --forward-to localhost:8000/stripe/webhook
//stripe trigger checkout.session.completed
class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Stripe API key - we'll do this lazily in methods if needed
    }
    
    private function initializeStripe()
    {
        static $initialized = false;
        
        if (!$initialized) {
            $stripeSecret = config('services.stripe.secret');
            
            if (empty($stripeSecret)) {
                $stripeSecret = env('STRIPE_SECRET');
            }
            
            if (empty($stripeSecret)) {
                throw new \Exception('Stripe API key not configured. Please set STRIPE_SECRET in your .env file.');
            }
            
            Stripe::setApiKey($stripeSecret);
            $initialized = true;
            
            Log::info('Stripe API key initialized', ['key_prefix' => substr($stripeSecret, 0, 7)]);
        }
    }

    // Show Payment Form
    public function showMakePaymentForm(Request $request)
    {
        $selectedOffer = null;
        
        if ($request->has('offer_id')) {
            $selectedOffer = \App\Models\Offer::where('id', $request->offer_id)
                ->where('is_active', true)
                ->where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->first();
        }
        
        // Get all available offers for selection
        $offers = \App\Models\Offer::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('make-payment', compact('selectedOffer', 'offers'));
    }

    public function createMeteredPayment(Request $request)
    {
        $this->initializeStripe();
        
        $amount = (int) ($request->input('amount') * 100); // Convert to cents
        $offerId = $request->input('offer_id');
        $user = Auth::user();

        Log::info('=== CREATE METERED PAYMENT REQUEST ===', [
            'user_id' => $user->id,
            'request_amount' => $request->input('amount'),
            'amount_in_cents' => $amount,
            'offer_id' => $offerId,
            'request_data' => $request->all()
        ]);

        try {
            // Get or create Stripe customer
            $stripeCustomer = $this->getOrCreateStripeCustomer($user);

            // Handle offer bonus calculation
            $offer = null;
            $bonusAmount = 0;
            $originalAmount = $amount / 100; // Convert back to dollars for calculation
            
            if ($offerId) {
                $offer = \App\Models\Offer::where('id', $offerId)
                    ->where('is_active', true)
                    ->where('valid_from', '<=', now())
                    ->where('valid_until', '>=', now())
                    ->first();
                    
                Log::info('Offer lookup result', [
                    'offer_id' => $offerId,
                    'offer_found' => $offer ? true : false,
                    'offer_name' => $offer ? $offer->name : null,
                    'offer_type' => $offer ? $offer->type : null,
                    'minimum_deposit' => $offer ? $offer->minimum_deposit : null,
                    'bonus_percentage' => $offer ? $offer->bonus_percentage : null
                ]);
                    
                if ($offer && $originalAmount >= ($offer->minimum_deposit ?: 0)) {
                    if ($offer->type === 'percentage_bonus' || $offer->type === 'first_deposit' || $offer->type === 'reload_bonus') {
                        $bonusAmount = $originalAmount * ($offer->bonus_percentage / 100);
                        if ($offer->maximum_bonus && $bonusAmount > $offer->maximum_bonus) {
                            $bonusAmount = $offer->maximum_bonus;
                        }
                    } elseif ($offer->type === 'fixed_bonus') {
                        $bonusAmount = $offer->bonus_fixed_amount;
                    }
                    
                    Log::info('Bonus calculation', [
                        'original_amount' => $originalAmount,
                        'offer_type' => $offer->type,
                        'bonus_percentage' => $offer->bonus_percentage,
                        'calculated_bonus' => $bonusAmount,
                        'meets_minimum' => $originalAmount >= ($offer->minimum_deposit ?: 0)
                    ]);
                } else {
                    Log::warning('Offer conditions not met', [
                        'original_amount' => $originalAmount,
                        'minimum_deposit' => $offer ? $offer->minimum_deposit : 'no_offer',
                        'meets_minimum' => $offer ? ($originalAmount >= ($offer->minimum_deposit ?: 0)) : false
                    ]);
                }
            } else {
                Log::info('No offer selected for payment');
            }

            Log::info('Processing metered payment', [
                'user_id' => $user->id,
                'amount' => $originalAmount,
                'bonus_amount' => $bonusAmount,
                'offer_id' => $offerId,
                'current_subscription_id' => $user->metered_subscription_id
            ]);

            // Check and setup metered billing if not active
            if (!$user->metered_subscription_id || 
                !$this->isSubscriptionActive($user->metered_subscription_id)) {
                
                Log::info('Creating new subscription for user', ['user_id' => $user->id]);
                
                // Create new subscription for recurring billing
                $subscription = \Stripe\Subscription::create([
                    'customer' => $stripeCustomer->id,
                    'items' => [[
                        'price' => config('services.stripe.metered_price_id'),
                    ]],
                    'payment_behavior' => 'default_incomplete',
                    'expand' => ['latest_invoice.payment_intent'],
                ]);
                
                User::where('id', $user->id)->update(['metered_subscription_id' => $subscription->id]);
            }

            // Create line items for the payment
            $productName = 'Credit Purchase';
            if ($bonusAmount > 0) {
                $productName .= ' + Bonus';
                if ($offer) {
                    $productName .= " ({$offer->name})";
                }
            }

            $lineItems = [[
                'price_data' => [
                    'currency' => 'mxn',
                    'product_data' => [
                        'name' => $productName,
                        'description' => $bonusAmount > 0 ? 
                            "You'll receive MX$" . number_format($originalAmount, 2) . " credit + MX$" . number_format($bonusAmount, 2) . " bonus" :
                            "You'll receive MX$" . number_format($originalAmount, 2) . " credit"
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]];

            // Create a Stripe Checkout session for subscription payment
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'customer' => $stripeCustomer->id,
                'line_items' => $lineItems,
                'mode' => 'payment', // One-time payment that gets added to recurring billing
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
                'metadata' => [
                    'user_id' => $user->id,
                    'offer_id' => $offerId,
                    'bonus_amount' => $bonusAmount,
                    'subscription_id' => $user->metered_subscription_id,
                ],
            ]);

            // Create a pending payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $originalAmount,
                'transaction_id' => $session->id,
                'status' => Payment::STATUS_PENDING,
                'type' => Payment::TYPE_DEPOSIT,
                'offer_id' => $offerId,
                'bonus_amount' => $bonusAmount,
            ]);

            Log::info('Payment session created', [
                'payment_id' => $payment->id,
                'session_id' => $session->id,
                'bonus_amount' => $bonusAmount
            ]);

            return response()->json(['sessionId' => $session->id]);

        } catch (\Exception $e) {
            Log::error('Payment creation failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Get or Create Stripe Customer
    private function getOrCreateStripeCustomer(User $user)
    {
        $this->initializeStripe();
        
        if ($user->stripe_customer_id) {
            return Customer::retrieve($user->stripe_customer_id);
        }

        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => ['user_id' => $user->id]
            ]);

            $user->update(['stripe_customer_id' => $customer->id]);

            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Stripe customer: ' . $e->getMessage());
            throw new \Exception('Failed to create Stripe customer');
        }
    }

    public function getBalance()
    {
        $this->initializeStripe();
        
        $user = Auth::user();
        
        try {
            // Get Stripe customer balance if customer exists
            $stripeBalance = 0;
            if ($user->stripe_customer_id) {
                $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);
                $stripeBalance = abs($customer->balance) / 100; // Convert from cents and ensure positive
            }

            return response()->json([
                // Current user balances from database
                'balance' => $user->balance,
                'bonus_balance' => $user->bonus_balance,
                'total_balance' => $user->total_balance,
                // Stripe customer balance
                'stripe_balance' => $stripeBalance
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Stripe balance: ' . $e->getMessage());
            
            // Return at least the database balance if Stripe fails
            return response()->json([
                'balance' => $user->balance ?? 0,
                'bonus_balance' => $user->bonus_balance ?? 0,
                'total_balance' => $user->total_balance ?? 0,
                'stripe_balance' => 0,
                'error' => 'Unable to fetch Stripe balance'
            ]);
        }
    }
   

    // Handle Payment Success
    public function handlePaymentSuccess(Request $request)
    {
        $this->initializeStripe();
        
        $sessionId = $request->query('session_id');
        
        Log::info('=== PAYMENT SUCCESS HANDLER CALLED ===', [
            'session_id' => $sessionId,
            'timestamp' => now()
        ]);
        
        try {
            // Retrieve the session from Stripe
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            
            Log::info('Payment success callback', [
                'session_id' => $sessionId,
                'payment_status' => $session->payment_status,
                'metadata' => $session->metadata,
                'amount_total' => $session->amount_total
            ]);

            // Find the payment record
            $payment = Payment::where('transaction_id', $sessionId)
                            ->where('status', Payment::STATUS_PENDING)
                            ->first();

            if (!$payment) {
                // Check if payment was already processed
                $existingPayment = Payment::where('transaction_id', $sessionId)->first();
                
                Log::warning('Payment not found or already processed', [
                    'session_id' => $sessionId,
                    'existing_payment_status' => $existingPayment ? $existingPayment->status : 'not_found',
                    'existing_payment_id' => $existingPayment ? $existingPayment->id : null
                ]);
                
                if ($existingPayment && $existingPayment->status === Payment::STATUS_COMPLETED) {
                    // Payment was already processed, redirect to success page
                    return redirect()->route('payment.success.page')
                                   ->with('payment_details', [
                                       'amount' => $existingPayment->amount,
                                       'bonus_amount' => $existingPayment->bonus_amount ?? 0,
                                       'total_added' => $existingPayment->amount + ($existingPayment->bonus_amount ?? 0),
                                       'offer_id' => $existingPayment->offer_id,
                                       'payment_id' => $existingPayment->id
                                   ]);
                }
                
                return redirect()->route('dashboard')
                               ->with('message', 'Payment is being processed');
            }

            Log::info('Found pending payment record', [
                'payment_id' => $payment->id,
                'payment_amount' => $payment->amount,
                'payment_bonus' => $payment->bonus_amount,
                'payment_offer_id' => $payment->offer_id
            ]);

            if ($session->payment_status === 'paid') {
                DB::transaction(function () use ($payment, $session, $sessionId) {
                    $user = User::find($payment->user_id);
                    
                    // Get metadata from session
                    $bonusAmount = isset($session->metadata['bonus_amount']) ? 
                                  floatval($session->metadata['bonus_amount']) : 
                                  ($payment->bonus_amount ?? 0);
                    $offerId = isset($session->metadata['offer_id']) ? 
                              $session->metadata['offer_id'] : 
                              $payment->offer_id;
                    
                    Log::info('Processing payment with bonus details', [
                        'user_id' => $user->id,
                        'session_bonus' => $session->metadata['bonus_amount'] ?? 'not_set',
                        'payment_bonus' => $payment->bonus_amount,
                        'final_bonus' => $bonusAmount,
                        'offer_id' => $offerId
                    ]);
                    
                    // Update payment status with bonus details
                    $payment->update([
                        'status' => Payment::STATUS_COMPLETED,
                        'bonus_amount' => $bonusAmount,
                    ]);

                    // Update user's main balance
                    $creditAmount = $payment->amount;
                    $user->increment('balance', $creditAmount);
                    
                    Log::info('Updated main balance', [
                        'user_id' => $user->id,
                        'credit_amount' => $creditAmount,
                        'new_balance' => $user->fresh()->balance
                    ]);
                    
                    // Add bonus to bonus balance if applicable
                    if ($bonusAmount > 0) {
                        $user->increment('bonus_balance', $bonusAmount);
                        
                        Log::info('Updated bonus balance', [
                            'user_id' => $user->id,
                            'bonus_amount' => $bonusAmount,
                            'new_bonus_balance' => $user->fresh()->bonus_balance
                        ]);
                        
                        // Create a separate payment record for the bonus
                        $bonusPayment = Payment::create([
                            'user_id' => $user->id,
                            'amount' => $bonusAmount,
                            'bonus_amount' => 0, // This is the bonus record itself, so bonus_amount should be 0
                            'type' => Payment::TYPE_BONUS,
                            'status' => Payment::STATUS_COMPLETED,
                            'offer_id' => $offerId,
                            'description' => 'Bonus from offer: ' . ($offerId ? "Offer ID $offerId" : 'Promotional Bonus'),
                            'transaction_id' => $sessionId . '_bonus',
                        ]);
                        
                        Log::info('Created bonus payment record', [
                            'bonus_payment_id' => $bonusPayment->id,
                            'bonus_amount' => $bonusAmount,
                            'offer_id' => $offerId
                        ]);
                    }

                    // Record metered usage for the subscription
                    if ($user->metered_subscription_id) {
                        try {
                            // Get the subscription to find the subscription item
                            $subscription = \Stripe\Subscription::retrieve($user->metered_subscription_id);
                            
                            if (!empty($subscription->items->data)) {
                                $subscriptionItemId = $subscription->items->data[0]->id;
                                
                                \Stripe\SubscriptionItem::createUsageRecord(
                                    $subscriptionItemId,
                                    [
                                        'quantity' => intval($creditAmount * 100), // Record usage in cents
                                        'timestamp' => time(),
                                    ]
                                );
                                
                                Log::info('Recorded metered usage', [
                                    'subscription_item_id' => $subscriptionItemId,
                                    'quantity' => intval($creditAmount * 100)
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::warning('Failed to record metered usage', [
                                'error' => $e->getMessage(),
                                'user_id' => $user->id,
                                'amount' => $creditAmount
                            ]);
                        }
                    }

                    Log::info('Payment processed in success callback', [
                        'user_id' => $user->id,
                        'amount' => $creditAmount,
                        'bonus_amount' => $bonusAmount,
                        'new_balance' => $user->fresh()->balance,
                        'new_bonus_balance' => $user->fresh()->bonus_balance
                    ]);
                });

                return redirect()->route('payment.success.page')
                               ->with('payment_details', [
                                   'amount' => $payment->amount,
                                   'bonus_amount' => $payment->bonus_amount ?? 0,
                                   'total_added' => $payment->amount + ($payment->bonus_amount ?? 0),
                                   'offer_id' => $payment->offer_id,
                                   'payment_id' => $payment->id
                               ]);
            }

            return redirect()->route('dashboard')
                           ->with('message', 'Payment is being processed');

        } catch (\Exception $e) {
            Log::error('Payment success handling failed', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')
                           ->with('error', 'There was an issue processing your payment');
        }
    }

    // Show Payment Success Page
    public function showPaymentSuccessPage()
    {
        $paymentDetails = session('payment_details');
        
        if (!$paymentDetails) {
            return redirect()->route('dashboard')
                           ->with('message', 'No payment details found');
        }
        
        return view('payment-success', ['paymentDetails' => $paymentDetails]);
    }

    // Handle Payment Cancellation
    public function handlePaymentCancel(Request $request)
    {
        $sessionId = $request->query('session_id');

        if ($sessionId) {
            $payment = Payment::where('transaction_id', $sessionId)->first();
            if ($payment) {
                $payment->update([
                    'status' => Payment::STATUS_FAILED,
                ]);
            }
        }

        return view('payment-cancel');
    }

    // Handle Stripe Webhooks
    public function handleStripeWebhook(Request $request)
    {
        $this->initializeStripe();
        
        try {
            $payload = $request->getContent();
            $sigHeader = $request->header('Stripe-Signature');
            $endpointSecret = config('services.stripe.webhook_secret');

            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );

            Log::info('Webhook event received', ['type' => $event->type]);

            switch ($event->type) {
                case 'checkout.session.completed':
                    return $this->handleCheckoutSessionCompleted($event->data->object);
                
                case 'checkout.session.expired':
                    return $this->handlePaymentCancelled($event->data->object, 'expired');
                    
                case 'payment_intent.payment_failed':
                    return $this->handlePaymentFailed($event->data->object);
                    
                case 'payment_intent.canceled':
                    return $this->handlePaymentCancelled($event->data->object, 'cancelled');
                    
                case 'payment_intent.processing':
                    return $this->handlePaymentProcessing($event->data->object);
                    
                default:
                    Log::info('Unhandled event type', ['type' => $event->type]);
                    return response()->json(['status' => 'ignored']);
            }

        } catch (\Exception $e) {
            Log::error('Webhook error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function handleCheckoutSessionCompleted($session)
    {
        Log::info('=== WEBHOOK HANDLER CALLED ===', [
            'session_id' => $session->id,
            'timestamp' => now(),
            'amount_total' => $session->amount_total,
            'metadata' => $session->metadata ?? []
        ]);

        try {
            DB::beginTransaction();

            // Find the payment
            $payment = Payment::where('transaction_id', $session->id)
                ->where('status', Payment::STATUS_PENDING)
                ->first();

            if (!$payment) {
                // Check if already processed
                $existingPayment = Payment::where('transaction_id', $session->id)->first();
                
                Log::warning('Payment not found in webhook or already processed', [
                    'session_id' => $session->id,
                    'existing_payment_status' => $existingPayment ? $existingPayment->status : 'not_found',
                    'existing_payment_id' => $existingPayment ? $existingPayment->id : null
                ]);
                
                if ($existingPayment && $existingPayment->status === Payment::STATUS_COMPLETED) {
                    DB::rollBack();
                    return response()->json(['status' => 'already_processed']);
                }
                
                Log::error('Payment not found', ['session_id' => $session->id]);
                DB::rollBack();
                return response()->json(['error' => 'Payment not found'], 404);
            }

            Log::info('Found payment record in webhook', [
                'payment_id' => $payment->id,
                'payment_amount' => $payment->amount,
                'payment_bonus' => $payment->bonus_amount,
                'payment_offer_id' => $payment->offer_id,
                'payment_status' => $payment->status
            ]);

            // Find the user
            $user = User::find($payment->user_id);
            if (!$user) {
                Log::error('User not found', ['payment_id' => $payment->id]);
                DB::rollBack();
                return response()->json(['error' => 'User not found'], 404);
            }

            // Get metadata from session
            $bonusAmount = isset($session->metadata['bonus_amount']) ? 
                          floatval($session->metadata['bonus_amount']) : 
                          ($payment->bonus_amount ?? 0);
            $offerId = isset($session->metadata['offer_id']) ? 
                      $session->metadata['offer_id'] : 
                      $payment->offer_id;

            Log::info('Processing webhook payment with bonus details', [
                'user_id' => $user->id,
                'session_bonus' => $session->metadata['bonus_amount'] ?? 'not_set',
                'payment_bonus' => $payment->bonus_amount,
                'final_bonus' => $bonusAmount,
                'offer_id' => $offerId
            ]);

            // Update payment status with bonus details
            $payment->update([
                'status' => Payment::STATUS_COMPLETED,
                'bonus_amount' => $bonusAmount,
            ]);

            // Update user's main balance
            $creditAmount = $payment->amount;
            $user->increment('balance', $creditAmount);
            
            Log::info('Updated main balance in webhook', [
                'user_id' => $user->id,
                'credit_amount' => $creditAmount,
                'new_balance' => $user->fresh()->balance
            ]);
            
            // Add bonus to bonus balance if applicable
            if ($bonusAmount > 0) {
                $user->increment('bonus_balance', $bonusAmount);
                
                Log::info('Updated bonus balance in webhook', [
                    'user_id' => $user->id,
                    'bonus_amount' => $bonusAmount,
                    'new_bonus_balance' => $user->fresh()->bonus_balance
                ]);
                
                // Create a separate payment record for the bonus
                $bonusPayment = Payment::create([
                    'user_id' => $user->id,
                    'amount' => $bonusAmount,
                    'bonus_amount' => 0, // This is the bonus record itself, so bonus_amount should be 0
                    'type' => Payment::TYPE_BONUS,
                    'status' => Payment::STATUS_COMPLETED,
                    'offer_id' => $offerId,
                    'description' => 'Bonus from offer: ' . ($offerId ? "Offer ID $offerId" : 'Promotional Bonus'),
                    'transaction_id' => $session->id . '_bonus',
                ]);
                
                Log::info('Created bonus payment record in webhook', [
                    'bonus_payment_id' => $bonusPayment->id,
                    'bonus_amount' => $bonusAmount,
                    'offer_id' => $offerId
                ]);
            }

            DB::commit();

            Log::info('Payment processed successfully in webhook', [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'amount' => $creditAmount,
                'bonus_amount' => $bonusAmount,
                'new_balance' => $user->fresh()->balance,
                'new_bonus_balance' => $user->fresh()->bonus_balance
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process payment in webhook', [
                'error' => $e->getMessage(),
                'session_id' => $session->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function handlePaymentFailed($paymentIntent)
    {
        return $this->updatePaymentStatus(
            $paymentIntent->metadata->payment_id ?? null,
            Payment::STATUS_FAILED,
            'Payment failed: ' . ($paymentIntent->last_payment_error->message ?? 'Unknown error')
        );
    }

    private function handlePaymentCancelled($object, $reason)
    {
        return $this->updatePaymentStatus(
            $object->metadata->payment_id ?? null,
            Payment::STATUS_CANCELLED,
            'Payment ' . $reason
        );
    }

    private function handlePaymentProcessing($paymentIntent)
    {
        return $this->updatePaymentStatus(
            $paymentIntent->metadata->payment_id ?? null,
            Payment::STATUS_PROCESSING,
            'Payment is being processed'
        );
    }

    private function updatePaymentStatus($paymentId, $status, $log)
    {
        try {
            DB::beginTransaction();

            $payment = Payment::where('transaction_id', $paymentId)
                ->first();

            if (!$payment) {
                Log::warning('Payment not found', ['payment_id' => $paymentId]);
                DB::rollBack();
                return response()->json(['error' => 'Payment not found'], 404);
            }

            $payment->update([
                'status' => $status,
            ]);

            DB::commit();

            Log::info('Payment status updated', [
                'payment_id' => $paymentId,
                'status' => $status
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update payment', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId
            ]);
            throw $e;
        }
    }

    // Payment Success View
    public function paymentSuccess()
    {
        return view('payment-success');
    }

    // Payment Cancel View
    public function paymentCancel()
    {
        return view('payment-cancel');
    }

    // Add this helper method
    private function isSubscriptionActive($subscriptionId)
    {
        $this->initializeStripe();
        
        try {
            $subscription = \Stripe\Subscription::retrieve($subscriptionId);
            Log::info('Checking subscription status', [
                'subscription_id' => $subscriptionId,
                'status' => $subscription->status
            ]);
            return $subscription->status === 'active';
        } catch (\Exception $e) {
            Log::warning('Failed to check subscription status', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function testWebhook()
    {
        Log::info('Test webhook endpoint hit');
        return response()->json(['status' => 'success']);
    }

    public function getPayments()
    {
        try {
            $payments = DB::table('payments')
                ->select([
                    'id',
                    'amount',
                    'bonus_amount',
                    'type',
                    'status',
                    'offer_id',
                    'description',
                    'transaction_id',
                    'created_at'
                ])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($payments);

        } catch (\Exception $e) {
            Log::error('Failed to fetch payments', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return response()->json(['error' => 'Failed to fetch payments'], 500);
        }
    }

    public function downloadInvoice(Payment $payment)
    {
        // Ensure the user can only access their own payments
        if ($payment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        try {
            // Format the invoice data
            $data = [
                'invoice_number' => 'INV-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                'date' => $payment->created_at->format('Y-m-d'),
                'amount' => number_format($payment->amount / 100, 2),
                'transaction_id' => $payment->transaction_id,
                'customer_name' => Auth::user()->name,
                'customer_email' => Auth::user()->email,
            ];

            // Generate PDF using a view
            $pdf = Pdf::loadView('invoices.template', $data);

            // Generate filename
            $filename = 'invoice-' . $data['invoice_number'] . '.pdf';

            // Return the PDF for download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error generating invoice: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate invoice'], 500);
        }
    }

    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payments', compact('payments'));
    }
}