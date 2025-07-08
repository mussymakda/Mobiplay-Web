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
use PDF;
use Carbon\Carbon;


//stripe listen --forward-to localhost:8000/stripe/webhook
//stripe trigger checkout.session.completed
class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    // Show Payment Form
    public function showMakePaymentForm()
    {
        return view('make-payment');
    }

    public function createMeteredPayment(Request $request)
    {
        $amount = (int) ($request->input('amount') * 100); // Convert to cents
        $user = Auth::user();

        try {
            // Get or create Stripe customer
            $stripeCustomer = $this->getOrCreateStripeCustomer($user);

            Log::info('Processing metered payment', [
                'user_id' => $user->id,
                'current_subscription_id' => $user->metered_subscription_id
            ]);

            // Check and setup metered billing if not active
            if (!$user->metered_subscription_id || 
                !$this->isSubscriptionActive($user->metered_subscription_id)) {
                
                Log::info('Creating new subscription for user', ['user_id' => $user->id]);
                
                // Create new subscription
                $subscription = \Stripe\Subscription::create([
                    'customer' => $stripeCustomer->id,
                    'items' => [[
                        'price' => env('STRIPE_METERED_PRICE_ID'),
                    ]],
                    'payment_behavior' => 'allow_incomplete',
                ]);
                
                $user->update(['metered_subscription_id' => $subscription->id]);
            }

            // Create a Stripe Checkout session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'customer' => $stripeCustomer->id,
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'mxn',
                        'product_data' => ['name' => 'Credit Purchase'],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
            ]);

            // Create a pending payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'transaction_id' => $session->id,
                'status' => 'pending',
                'type' => 'credit_purchase'
            ]);

            Log::info('Payment session created', [
                'payment_id' => $payment->id,
                'session_id' => $session->id
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
        $user = Auth::user();
        
        try {
            // Get Stripe customer balance if customer exists
            $stripeBalance = 0;
            if ($user->stripe_customer_id) {
                $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);
                $stripeBalance = abs($customer->balance) / 100; // Convert from cents and ensure positive
            }

            return response()->json([
                // Current user credit balance from database
                'credit_balance' => $user->credit_balance,
                // Stripe customer balance
                'stripe_balance' => $stripeBalance
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Stripe balance: ' . $e->getMessage());
            
            // Return at least the database balance if Stripe fails
            return response()->json([
                'credit_balance' => 0,
                'stripe_balance' => 0,
                'error' => 'Unable to fetch Stripe balance'
            ]);
        }
    }
   

    // Handle Payment Success
    public function handlePaymentSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        
        try {
            // Retrieve the session from Stripe
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            
            Log::info('Payment success callback', [
                'session_id' => $sessionId,
                'payment_status' => $session->payment_status
            ]);

            // Find the payment record
            $payment = Payment::where('transaction_id', $sessionId)
                            ->where('status', 'pending')
                            ->first();

            if (!$payment) {
                Log::warning('Payment not found or already processed', [
                    'session_id' => $sessionId
                ]);
                return redirect()->route('dashboard')
                               ->with('message', 'Payment is being processed');
            }

            if ($session->payment_status === 'paid') {
                DB::transaction(function () use ($payment, $session) {
                    $user = User::find($payment->user_id);
                    
                    // Update payment status
                    $payment->update([
                        'status' => 'completed',
                        'log' => 'Payment completed via success callback'
                    ]);

                    // Update user's credit balance
                    $creditAmount = $session->amount_total / 100;
                    $user->increment('credit_balance', $creditAmount);

                    Log::info('Payment processed in success callback', [
                        'user_id' => $user->id,
                        'amount' => $creditAmount,
                        'new_balance' => $user->fresh()->credit_balance
                    ]);
                });

                return redirect()->route('dashboard')
                               ->with('success', 'Payment processed successfully');
            }

            return redirect()->route('dashboard')
                           ->with('message', 'Payment is being processed');

        } catch (\Exception $e) {
            Log::error('Payment success handling failed', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId
            ]);
            return redirect()->route('dashboard')
                           ->with('error', 'There was an issue processing your payment');
        }
    }

    // Handle Payment Cancellation
    public function handlePaymentCancel(Request $request)
    {
        $sessionId = $request->query('session_id');

        if ($sessionId) {
            $payment = Payment::where('transaction_id', $sessionId)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'log' => 'Payment was cancelled by the user'
                ]);
            }
        }

        return view('payment-cancel');
    }

    // Handle Stripe Webhooks
    public function handleStripeWebhook(Request $request)
    {
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
        Log::info('Processing checkout session', [
            'session_id' => $session->id,
            'amount' => $session->amount_total
        ]);

        try {
            DB::beginTransaction();

            // Find the payment
            $payment = Payment::where('transaction_id', $session->id)
                ->where('status', 'pending')
                ->first();

            if (!$payment) {
                Log::error('Payment not found', ['session_id' => $session->id]);
                DB::rollBack();
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Find the user
            $user = User::find($payment->user_id);
            if (!$user) {
                Log::error('User not found', ['payment_id' => $payment->id]);
                DB::rollBack();
                return response()->json(['error' => 'User not found'], 404);
            }

            // Update payment status
            $payment->update([
                'status' => 'completed',
                'log' => json_encode([
                    'webhook_processed' => true,
                    'session_id' => $session->id,
                    'amount' => $session->amount_total
                ])
            ]);

            // Update user's credit balance
            $creditAmount = $session->amount_total / 100;
            $user->increment('credit_balance', $creditAmount);

            DB::commit();

            Log::info('Payment processed successfully', [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'amount' => $creditAmount,
                'new_balance' => $user->fresh()->credit_balance
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process payment', [
                'error' => $e->getMessage(),
                'session_id' => $session->id
            ]);
            throw $e;
        }
    }

    private function handlePaymentFailed($paymentIntent)
    {
        return $this->updatePaymentStatus(
            $paymentIntent->metadata->payment_id ?? null,
            'failed',
            'Payment failed: ' . ($paymentIntent->last_payment_error->message ?? 'Unknown error')
        );
    }

    private function handlePaymentCancelled($object, $reason)
    {
        return $this->updatePaymentStatus(
            $object->metadata->payment_id ?? null,
            'cancelled',
            'Payment ' . $reason
        );
    }

    private function handlePaymentProcessing($paymentIntent)
    {
        return $this->updatePaymentStatus(
            $paymentIntent->metadata->payment_id ?? null,
            'processing',
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
                'log' => $log
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
                    'status',
                    'created_at'
                ])
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($payments);

        } catch (\Exception $e) {
            Log::error('Failed to fetch payments', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            return response()->json(['error' => 'Failed to fetch payments'], 500);
        }
    }

    public function downloadInvoice(Payment $payment)
    {
        // Ensure the user can only access their own payments
        if ($payment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        try {
            // Format the invoice data
            $data = [
                'invoice_number' => 'INV-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                'date' => $payment->created_at->format('Y-m-d'),
                'amount' => number_format($payment->amount / 100, 2),
                'transaction_id' => $payment->transaction_id,
                'customer_name' => auth()->user()->name,
                'customer_email' => auth()->user()->email,
            ];

            // Generate PDF using a view
            $pdf = PDF::loadView('invoices.template', $data);

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