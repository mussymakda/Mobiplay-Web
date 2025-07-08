<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    // Show the payment form
    public function showMakePaymentForm()
    {
        return view('make-payment');
    }

    // Create Stripe Checkout Session
    // Create Stripe Checkout Session for Recurring Payment
public function createStripeSession(Request $request)
{
    // Set your secret key (from .env)
    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        // Create a Stripe Checkout session for subscription
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'mxn', // Set currency to Mexican Pesos
                        'product_data' => [
                            'name' => 'Mobiplay Monthly Plan',
                        ],
                        'recurring' => [
                            'interval' => 'month', // Set to monthly
                            'interval_count' => 1, // Every month
                        ],
                        'unit_amount' => 300000, // 3000 MXN in cents
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription', // Use subscription mode
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return response()->json(['sessionId' => $session->id]);

    } catch (\Exception $e) {
        // Log error if Stripe session creation fails
        Log::error('Error creating Stripe session: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while creating the payment session.'], 500);
    }
}
public function createMeteredPayment(Request $request)
{
    $amount = $request->input('amount'); // Initial deposit in cents

    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        // Create a subscription (metered billing)
        $customer = \Stripe\Customer::create(['email' => $request->user()->email]);

        $product = \Stripe\Product::create(['name' => 'monthly_billing']);

        $price = \Stripe\Price::create([
            'unit_amount' => $amount,
            'currency' => 'php',
            'recurring' => [
                'interval' => 'month',
                'usage_type' => 'metered',
            ],
            'product' => $product->id,
        ]);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer' => $customer->id,
            'line_items' => [[
                'price' => $price->id,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return response()->json(['sessionId' => $session->id]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    // Handle Payment Success
    public function handlePaymentSuccess(Request $request)
{
    // Retrieve the session ID from the query parameters
    $sessionId = $request->query('session_id');

    // Ensure the session ID is provided
    if (!$sessionId) {
        return redirect()->route('payment.cancel')->with('error', 'Session ID not provided.');
    }

    // Set Stripe API key
    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        // Retrieve the session from Stripe
        $session = Session::retrieve($sessionId);

        // Find the payment in the database
        $paymentId = $request->query('payment_id');
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return redirect()->route('payment.cancel')->with('error', 'Payment record not found.');
        }

        // Update the payment record with transaction details
        $payment->status = 'Completed';
        $payment->transaction_id = $session->id; // Use the Stripe session ID as the transaction ID
        $payment->log = 'Payment completed successfully.';
        $payment->save();

        // Redirect to the success page
        return redirect()->route('payment.success')->with('success', 'Payment completed successfully.');

    } catch (\Exception $e) {
        // Log the error and redirect to the cancel page
        Log::error('Error handling payment success: ' . $e->getMessage());
        return redirect()->route('payment.cancel')->with('error', 'An error occurred while processing your payment.');
    }
}

    // Handle Payment Cancel
    public function handlePaymentCancel(Request $request)
    {
        $paymentId = $request->query('payment_id');
        if ($paymentId) {
            $payment = Payment::find($paymentId);
            if ($payment) {
                $payment->status = 'Failed';
                $payment->log = 'Payment was cancelled by the user.';
                $payment->save();
            }
        }

        return view('payment-cancel');
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
}
