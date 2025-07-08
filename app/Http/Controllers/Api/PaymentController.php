<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DepositService;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $depositService;

    public function __construct(DepositService $depositService)
    {
        $this->depositService = $depositService;
    }

    /**
     * Create a deposit intent
     */
    public function createDeposit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:10|max:10000',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);
            
            // In a real application, you would create a Stripe Payment Intent here
            $stripePaymentId = 'pi_' . uniqid() . '_demo';
            
            $payment = $this->depositService->processDeposit($user, $request->amount, [
                'stripe_payment_id' => $stripePaymentId,
                'stripe_customer_id' => $user->stripe_customer_id ?? ('cus_' . uniqid()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'payment' => [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'bonus_amount' => $payment->bonus_amount,
                    'offer' => $payment->offer ? [
                        'id' => $payment->offer->id,
                        'name' => $payment->offer->name,
                        'description' => $payment->offer->description,
                    ] : null,
                    'stripe_payment_id' => $payment->stripe_payment_id,
                    'status' => $payment->status,
                ],
                'message' => 'Deposit created successfully. Complete payment with Stripe.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create deposit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete a deposit (webhook from Stripe)
     */
    public function completeDeposit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|exists:payments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payment = Payment::findOrFail($request->payment_id);
            
            if ($this->depositService->completeDeposit($payment)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Deposit completed successfully',
                    'user_balance' => [
                        'balance' => $payment->user->balance,
                        'bonus_balance' => $payment->user->bonus_balance,
                        'total_balance' => $payment->user->total_balance,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete deposit'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete deposit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process ad spending
     */
    public function processAdSpend(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'user_id' => 'required|exists:users,id',
            'campaign_id' => 'nullable|string',
            'campaign_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);
            
            $payment = $this->depositService->processAdSpend($user, $request->amount, [
                'campaign_id' => $request->campaign_id,
                'campaign_name' => $request->campaign_name,
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'payment' => [
                    'id' => $payment->id,
                    'amount' => abs($payment->amount),
                    'type' => $payment->type,
                    'status' => $payment->status,
                ],
                'user_balance' => [
                    'balance' => $user->fresh()->balance,
                    'bonus_balance' => $user->fresh()->bonus_balance,
                    'total_balance' => $user->fresh()->total_balance,
                ],
                'message' => 'Ad spend processed successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get user balance
     */
    public function getBalance(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($request->user_id);

        return response()->json([
            'success' => true,
            'balance' => [
                'balance' => $user->balance,
                'bonus_balance' => $user->bonus_balance,
                'total_balance' => $user->total_balance,
                'formatted_balance' => $user->formatted_balance,
                'formatted_bonus_balance' => $user->formatted_bonus_balance,
                'formatted_total_balance' => $user->formatted_total_balance,
                'auto_debit_enabled' => $user->auto_debit_enabled,
                'auto_debit_threshold' => $user->auto_debit_threshold,
            ]
        ]);
    }
}
