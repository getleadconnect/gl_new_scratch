<?php

namespace App\Http\Controllers;

use App\Models\CampaignGift;
use App\Models\ScratchCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RedeemController extends Controller
{
    /**
     * Show the redeem scratch page.
     */
    public function index(): View
    {
        return view('user.redeem.index', [
            'pageTitle' => 'Redeem Scratch',
        ]);
    }

    /**
     * Search customer by Unique ID.
     */
    public function search(Request $request)
    {
        $request->validate([
            'unique_id' => 'required|string|max:50',
        ]);

        $userId   = auth()->user()->id;
        $uniqueId = trim($request->unique_id);

        $customer = ScratchCustomer::with('campaign')
            ->where('user_id', $userId)
            ->where('unique_id', $uniqueId)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'No record found for this Unique ID.',
            ], 404);
        }

        // Load associated gift
        $gift     = null;
        $giftImage = null;
        if ($customer->campaign_gift_id) {
            $gift = CampaignGift::find($customer->campaign_gift_id);
            if ($gift && $gift->gift_image) {
                $giftImage = asset('uploads/' . $gift->gift_image);
            }
        }

        return response()->json([
            'success'  => true,
            'customer' => [
                'id'           => $customer->id,
                'campaign'     => $customer->campaign ? $customer->campaign->campaign_name : '--',
                'name'         => $customer->name ?? '--',
                'mobile'       => $customer->cust_mobile ?? $customer->mobile ?? '--',
                'email'        => $customer->email ?? '--',
                'bill_no'      => $customer->bill_no ?? '--',
                'branch_id'    => $customer->branch_id ?? '--',
                'offer_text'   => $customer->offer_text ?? '--',
                'win_status'   => (int) $customer->win_status,
                'redeem'       => (int) $customer->redeem,
                'redeemed_on'  => $customer->redeemed_on
                                    ? Carbon::parse($customer->redeemed_on)->format('d-m-Y H:i')
                                    : null,
                'gift_image'   => $giftImage,
            ],
        ]);
    }

    /**
     * Mark customer scratch as redeemed.
     */
    public function redeemNow(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
        ]);

        $userId   = auth()->user()->id;
        $customer = ScratchCustomer::where('id', $request->customer_id)
            ->where('user_id', $userId)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer record not found.',
            ], 404);
        }

        if ($customer->redeem == 1) {
            return response()->json([
                'success' => false,
                'message' => 'This scratch has already been redeemed on ' .
                             Carbon::parse($customer->redeemed_on)->format('d-m-Y H:i') . '.',
            ], 422);
        }

        $customer->redeem         = 1;
        $customer->redeemed_on    = Carbon::now();
        $customer->redeemed_agent = $userId;
        $customer->save();

        return response()->json([
            'success'     => true,
            'message'     => 'Redeemed successfully.',
            'redeemed_on' => Carbon::parse($customer->redeemed_on)->format('d-m-Y H:i'),
        ]);
    }
}
