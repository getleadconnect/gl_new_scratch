<?php

namespace App\Http\Controllers;

use App\Models\ScratchCount;
use App\Models\ScratchPackage;
use App\Models\PurchaseScratchHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ScratchPurchaseController extends Controller
{
    /**
     * Display the purchase credits page.
     */
    public function index(): View
    {
        $userId = auth()->user()->id;
        $scratchPackages = ScratchPackage::orderBy('id', 'ASC')->get();
        $scratchCount = ScratchCount::where('user_id', $userId)->first();

        return view('user.settings.purchase-credits', [
            'pageTitle' => 'Purchase Credits',
            'scratchPackages' => $scratchPackages,
            'balanceCount' => $scratchCount->balance_count ?? 0,
        ]);
    }

    
    /**
     * Create a Razorpay order for scratch purchase.
     */
    public function createOrder(Request $request): JsonResponse
    {
        $request->validate([
            'scratch_count' => ['required', 'integer'],
        ]);

        $scratchCount = (int) $request->scratch_count;

        // Get package rate from DB
        $package = ScratchPackage::where('scratch_count', $scratchCount)->first();
        if (!$package) {
            return response()->json(['success' => false, 'message' => 'Invalid scratch package.'], 422);
        }

        $amount     = $package->total_amount;
        $amountPaise = (int) ($amount * 100);

        $response = Http::withBasicAuth(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'))
            ->post('https://api.razorpay.com/v1/orders', [
                'amount'   => $amountPaise,
                'currency' => 'INR',
                'receipt'  => 'pur_' . auth()->id() . '_' . time(),
            ]);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway error. Please try again.',
            ], 500);
        }

        $order = $response->json();

        return response()->json([
            'success'        => true,
            'order_id'       => $order['id'],
            'amount'         => $amountPaise,
            'amount_display' => $amount,
            'currency'       => 'INR',
            'razorpay_key'   => env('RAZORPAY_KEY'),
        ]);
    }

    /**
     * Verify Razorpay payment and add scratch counts.
     */
    public function verifyPayment(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'razorpay_order_id'   => ['required', 'string'],
                'razorpay_payment_id' => ['required', 'string'],
                'razorpay_signature'  => ['required', 'string'],
                'scratch_count'       => ['required', 'integer', 'min:1'],
            ]);

            $scratchCount = (int) $request->scratch_count;

            $package = ScratchPackage::where('scratch_count', $scratchCount)->first();
            if (!$package) {
                return response()->json(['success' => false, 'message' => 'Invalid scratch package.'], 422);
            }

            // Verify Razorpay signature
            $expectedSignature = hash_hmac(
                'sha256',
                $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
                env('RAZORPAY_SECRET')
            );

            if ($expectedSignature !== $request->razorpay_signature) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed. Please contact support.',
                ], 422);
            }

            $userId = auth()->user()->id;
            $amount = $package->total_amount;
            DB::beginTransaction();

            // Update or create scratch count
            $sc = ScratchCount::where('user_id', $userId)->first();
            if ($sc) {
                $sc->total_count   += $scratchCount;
                $sc->balance_count += $scratchCount;
                $sc->save();
            } else {
                ScratchCount::create([
                    'user_id'       => $userId,
                    'total_count'   => $scratchCount,
                    'used_count'    => 0,
                    'balance_count' => $scratchCount,
                ]);
            }

            // Deactivate previous purchase history
            PurchaseScratchHistory::where('user_id', $userId)
                ->where('status', 1)
                ->update(['status' => 0]);

            // Add purchase history
            PurchaseScratchHistory::create([
                'user_id'       => $userId,
                'narration'     => 'Purchased ' . number_format($scratchCount) . ' scratches via Razorpay on ' . date('d-m-Y'),
                'scratch_count' => $scratchCount,
                'amount'        => $amount,
                'status'        => 1,
            ]);

            // Record payment history
            DB::table('payment_history')->insert([
                'user_id'             => $userId,
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
                'scratch_count'       => $scratchCount,
                'amount'              => $amount,
                'currency'            => 'INR',
                'status'              => 'success',
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Purchase successful! ' . number_format($scratchCount) . ' scratches added.',
                'redirect' => route('user.dashboard'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Purchase failed. Please contact support.',
            ], 500);
        }
    }





    /**
     * Create a Razorpay order for admin purchasing credits for a child user.
     */
    public function adminCreateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'scratch_count' => ['required', 'integer'],
            'user_id'       => ['required', 'integer'],
        ]);

        $scratchCount = (int) $request->scratch_count;

        $package = ScratchPackage::where('scratch_count', $scratchCount)->first();
        if (!$package) {
            return response()->json(['success' => false, 'message' => 'Invalid scratch package.'], 422);
        }

        $amount      = $package->total_amount;
        $amountPaise = (int) ($amount * 100);

        $response = Http::withBasicAuth(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'))
            ->post('https://api.razorpay.com/v1/orders', [
                'amount'   => $amountPaise,
                'currency' => 'INR',
                'receipt'  => 'admin_pur_' . $request->user_id . '_' . time(),
            ]);

        if (!$response->successful()) {
            return response()->json(['success' => false, 'message' => 'Payment gateway error.'], 500);
        }

        $order = $response->json();

        return response()->json([
            'success'        => true,
            'order_id'       => $order['id'],
            'amount'         => $amountPaise,
            'amount_display' => $amount,
            'currency'       => 'INR',
            'razorpay_key'   => env('RAZORPAY_KEY'),
        ]);
    }

    /**
     * Verify Razorpay payment and add scratch credits to a child user.
     */
    public function adminVerifyPayment(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'razorpay_order_id'   => ['required', 'string'],
                'razorpay_payment_id' => ['required', 'string'],
                'razorpay_signature'  => ['required', 'string'],
                'scratch_count'       => ['required', 'integer', 'min:1'],
                'user_id'             => ['required', 'integer'],
            ]);

            $scratchCount = (int) $request->scratch_count;
            $userId       = (int) $request->user_id;

            $package = ScratchPackage::where('scratch_count', $scratchCount)->first();
            if (!$package) {
                return response()->json(['success' => false, 'message' => 'Invalid scratch package.'], 422);
            }

            $expectedSignature = hash_hmac(
                'sha256',
                $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
                env('RAZORPAY_SECRET')
            );

            if ($expectedSignature !== $request->razorpay_signature) {
                return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 422);
            }

            $amount = $package->total_amount;

            DB::beginTransaction();

            $sc = ScratchCount::where('user_id', $userId)->first();
            if ($sc) {
                $sc->total_count   += $scratchCount;
                $sc->balance_count += $scratchCount;
                $sc->save();
            } else {
                ScratchCount::create([
                    'user_id'       => $userId,
                    'total_count'   => $scratchCount,
                    'used_count'    => 0,
                    'balance_count' => $scratchCount,
                ]);
            }

            PurchaseScratchHistory::where('user_id', $userId)
                ->where('status', 1)
                ->update(['status' => 0]);

            PurchaseScratchHistory::create([
                'user_id'       => $userId,
                'narration'     => 'Purchased ' . number_format($scratchCount) . ' scratches via Razorpay (Admin) on ' . date('d-m-Y'),
                'scratch_count' => $scratchCount,
                'amount'        => $amount,
                'status'        => 1,
            ]);

            DB::table('payment_history')->insert([
                'user_id'             => $userId,
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
                'scratch_count'       => $scratchCount,
                'amount'              => $amount,
                'currency'            => 'INR',
                'status'              => 'success',
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase successful! ' . number_format($scratchCount) . ' scratches added.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Purchase failed. Please contact support.'], 500);
        }
    }
}
