<?php

namespace App\Http\Controllers;

use App\Models\ScratchCount;
use App\Models\Campaign;
use App\Models\ScratchCustomer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin (role_id 1) dashboard.
     */
    public function index(): View
    {
        $userId = auth()->user()->id;

        // Aggregate scratch counts across all child users (role_id 3, parent_id = admin)
        $childUserIds = User::where('role_id', 3)
            ->where('parent_id', $userId)
            ->pluck('id');

        $scratchTotals = ScratchCount::whereIn('user_id', $childUserIds)
            ->selectRaw('SUM(total_count) as total_count, SUM(used_count) as used_count, SUM(balance_count) as balance_count')
            ->first();

        $user     = auth()->user();
        $start    = $user->subscription_start_date;
        $end      = $user->subscription_end_date;
        $isActive = $end && \Carbon\Carbon::parse($end)->endOfDay()->isFuture();

        // Campaign bar chart data
        $campaigns = Campaign::where('user_id', $userId)
            ->whereNull('deleted_at')
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

        // Child users (role_id 3) whose parent_id matches this admin
        $childUsers = User::where('role_id', 3)
            ->where('parent_id', $userId)
            ->leftJoin('scratch_counts', 'users.id', '=', 'scratch_counts.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.mobile',
                'users.status',
                'scratch_counts.total_count',
                'scratch_counts.used_count',
                'scratch_counts.balance_count'
            )
            ->get();

        return view('admin.admin-dashboard', [
            'pageTitle'          => 'Dashboard',
            'totalCount'         => $scratchTotals->total_count   ?? 0,
            'usedCount'          => $scratchTotals->used_count    ?? 0,
            'balanceCount'       => $scratchTotals->balance_count ?? 0,
            'subscriptionActive' => $isActive,
            'subscriptionStart'  => $start ? \Carbon\Carbon::parse($start)->format('d M Y') : null,
            'subscriptionEnd'    => $end   ? \Carbon\Carbon::parse($end)->format('d M Y')   : null,
            'chartLabels'        => $chartLabels,
            'chartWin'           => $chartWin,
            'chartLoss'          => $chartLoss,
            'childUsers'         => $childUsers,
        ]);
    }
}
