<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ScratchCount;
use App\Models\ScratchPackage;
use App\Models\PurchaseScratchHistory;
use App\Models\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Display the registration wizard.
     */
    public function showRegistrationForm(): View
    {
        $scratchPackage=ScratchPackage::orderBy('id','ASC')->get();
        return view('auth.register',compact('scratchPackage'));
    }

    /**
     * Fallback (unused — payment flow handles user creation).
     */
    public function register(Request $request): RedirectResponse
    {
        return redirect()->route('register');
    }

    /**
     * Step 2 → 3: Validate all form data, create a Razorpay order, return order details.
     */
    public function createOrder(Request $request): JsonResponse
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'country_code'  => ['required', 'string', 'max:10'],
            'mobile'        => ['required', 'string', 'max:20'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'scratch_count' => ['required', 'integer'],
        ]);

        $scratchCount = (int) $request->scratch_count;

        // Get package rate from DB
        $package = ScratchPackage::where('scratch_count', $scratchCount)->first();
        if (!$package) {
            return response()->json(['success' => false, 'message' => 'Invalid scratch package.'], 422);
        }

        if ($request->scratch_count <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid scratch credit.'], 422);
        }

        $amount      = $package->total_amount;
        $amountPaise = (int) ($amount * 100);

        $response = Http::withBasicAuth(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'))
            ->post('https://api.razorpay.com/v1/orders', [
                'amount'   => $amountPaise,
                'currency' => 'INR',
                'receipt'  => 'reg_' . time(),
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
     * Step 3: Verify Razorpay signature, create user + records, login.
     */
    public function verifyPayment(Request $request): JsonResponse
    {
        $request->validate([
            'razorpay_order_id'   => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature'  => ['required', 'string'],
            'name'                => ['required', 'string', 'max:255'],
            'email'               => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'country_code'        => ['required', 'string', 'max:10'],
            'mobile'              => ['required', 'string', 'max:20'],
            'password'            => ['required', 'string', 'min:8'],
            'scratch_count'       => ['required', 'integer'],
        ]);

        if ($request->scratch_count <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid scratch count.'], 422);
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

        $scratchCount = (int) $request->scratch_count;
        $package      = ScratchPackage::where('scratch_count', $scratchCount)->first();
        if (!$package) {
            return response()->json(['success' => false, 'message' => 'Invalid scratch package.'], 422);
        }
        $amount = $package->total_amount;

        try {
            DB::beginTransaction();

            $user = User::create([
                'name'                   => $request->name,
                'email'                  => $request->email,
                'country_code'           => $request->country_code,
                'mobile'                 => $request->mobile,
                'user_mobile'            => $request->country_code . $request->mobile,
                'password'               => Hash::make($request->password),
                'role_id'                => 2,
                'status'                 => 1,
                'subscription_start_date'=> now()->toDateString(),
                'subscription_end_date'  => now()->addYear()->toDateString(),
            ]);

            // Generate unique_id
            $le     = strlen((string) $user->id);
            $uniqId = 'GS' . str_pad('', (8 - $le), '0') . $user->id;
            $user->update(['unique_id' => $uniqId]);

            // Create scratch count record
            ScratchCount::create([
                'user_id'       => $user->id,
                'total_count'   => $scratchCount,
                'used_count'    => 0,
                'balance_count' => $scratchCount,
            ]);

            // Create settings
            $sdata = [
                'settings_type' => "otp_enabled",
                'settings_value' => "Enabled",
                'user_id' => $user->id,
                'status' => 1,
            ];

            Settings::create($sdata);

            // Deactivate previous purchase history
            PurchaseScratchHistory::where('user_id', $user->id)
                ->where('status', 1)
                ->update(['status' => 0]);

            // Add purchase history
            PurchaseScratchHistory::create([
                'user_id'       => $user->id,
                'narration'     => 'Purchased ' . number_format($scratchCount) . ' scratches via Razorpay on ' . date('d-m-Y'),
                'scratch_count' => $scratchCount,
                'amount'        =>$amount,
                'status'        => 1,
            ]);

            // Record payment history
            DB::table('payment_history')->insert([
                'user_id'             => $user->id,
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

            Auth::login($user);

            return response()->json([
                'success'  => true,
                'message'  => 'Registration successful!',
                'redirect' => route('user.dashboard'),
            ]);

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please contact support.',
            ], 500);
        }
    }
}
