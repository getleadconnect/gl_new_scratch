<?php

namespace App\Http\Controllers;

use App\Models\ScratchCount;
use App\Models\ScratchPackage;
use App\Models\Campaign;
use App\Models\ScratchCustomer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(): View
    {
        $userId = auth()->user()->id;

        $scratchCount = ScratchCount::where('user_id', $userId)->first();

        $user     = auth()->user();
        $start    = $user->subscription_start_date;
        $end      = $user->subscription_end_date;
        $isActive = $end && \Carbon\Carbon::parse($end)->endOfDay()->isFuture();

        // Campaign bar chart data
        $campaigns = Campaign::where('user_id', $userId)
            ->whereNull('deleted_at')->where('status',1)
            ->get(['id', 'campaign_name']);

        $chartLabels = [];
        $chartWin    = [];
        $chartLoss   = [];
        foreach ($campaigns as $campaign) {
            $chartLabels[] = $campaign->campaign_name;
            $chartWin[]    = ScratchCustomer::where('campaign_id', $campaign->id)
                                ->whereNull('deleted_at')
                                ->where('win_status', 1)
                                ->count();
            $chartLoss[]   = ScratchCustomer::where('campaign_id', $campaign->id)
                                ->whereNull('deleted_at')
                                ->where('win_status', 0)
                                ->count();
        }

        $scratchPackages = ScratchPackage::orderBy('id', 'ASC')->get();

        return view('user.dashboard', [
            'pageTitle'          => 'Dashboard',
            'totalCount'         => $scratchCount->total_count   ?? 0,
            'usedCount'          => $scratchCount->used_count    ?? 0,
            'balanceCount'       => $scratchCount->balance_count ?? 0,
            'subscriptionActive' => $isActive,
            'subscriptionStart'  => $start ? \Carbon\Carbon::parse($start)->format('d M Y') : null,
            'subscriptionEnd'    => $end   ? \Carbon\Carbon::parse($end)->format('d M Y')   : null,
            'chartLabels'        => $chartLabels,
            'chartWin'           => $chartWin,
            'chartLoss'          => $chartLoss,
            'scratchPackages'    => $scratchPackages,
        ]);
    }
}
