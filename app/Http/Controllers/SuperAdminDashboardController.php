<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ScratchCount;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminDashboardController extends Controller
{
    public function create()
    {
        // Admin (role_id 1) gets their own personal dashboard
        if (auth()->user()->role_id === 1) {
            return app(AdminDashboardController::class)->index();
        }

        $today = now()->toDateString();

        // Only count regular users (role_id 2 = user, 3 = child)
        $totalUsers   = User::whereIn('role_id', [2, 3])->whereNull('deleted_at')->count();
        $activeUsers  = User::whereIn('role_id', [2, 3])->whereNull('deleted_at')
                            ->whereNotNull('subscription_end_date')
                            ->where('subscription_end_date', '>=', $today)
                            ->count();
        $expiredUsers = User::whereIn('role_id', [2, 3])->whereNull('deleted_at')
                            ->where(function ($q) use ($today) {
                                $q->whereNull('subscription_end_date')
                                  ->orWhere('subscription_end_date', '<', $today);
                            })
                            ->count();
        $totalScratch = ScratchCount::sum('total_count');

        // Monthly subscriptions (current year Jan-Dec)
        $currentYear = now()->year;
        $monthlyLabels = [];
        $monthlyData   = [];
        $trendLabels   = [];
        $trendActive   = [];
        $trendExpired  = [];

        for ($m = 1; $m <= 12; $m++) {
            $date = Carbon::createFromDate($currentYear, $m, 1);
            $monthlyLabels[] = $date->format('M');
            $endOfMonth = $date->endOfMonth()->toDateString();

            $monthlyData[] = User::whereIn('role_id', [2, 3])
                ->whereNull('deleted_at')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->count();

            $trendLabels[] = $date->format('M');

            $trendActive[] = User::whereIn('role_id', [2, 3])
                ->whereNull('deleted_at')
                ->where('created_at', '<=', $endOfMonth)
                ->whereNotNull('subscription_end_date')
                ->where('subscription_end_date', '>=', $endOfMonth)
                ->count();

            $trendExpired[] = User::whereIn('role_id', [2, 3])
                ->whereNull('deleted_at')
                ->where('created_at', '<=', $endOfMonth)
                ->where(function ($q) use ($endOfMonth) {
                    $q->whereNull('subscription_end_date')
                      ->orWhere('subscription_end_date', '<', $endOfMonth);
                })
                ->count();
        }

        return view('admin.dashboard', [
            'pageTitle'      => 'Dashboard',
            'totalUsers'     => $totalUsers,
            'activeUsers'    => $activeUsers,
            'expiredUsers'   => $expiredUsers,
            'totalScratch'   => $totalScratch,
            'monthlyLabels'  => $monthlyLabels,
            'monthlyData'    => $monthlyData,
            'trendLabels'    => $trendLabels,
            'trendActive'    => $trendActive,
            'trendExpired'   => $trendExpired,
        ]);
    }

    /**
     * Get chart data for a specific year (AJAX).
     */
    public function chartData(Request $request)
    {
        $year = (int) ($request->year ?: now()->year);

        $monthLabels = [];
        $monthlyData = [];
        $trendActive = [];
        $trendExpired = [];

        for ($m = 1; $m <= 12; $m++) {
            $date = Carbon::createFromDate($year, $m, 1);
            $monthLabels[] = $date->format('M');
            $endOfMonth = $date->endOfMonth()->toDateString();

            $monthlyData[] = User::whereIn('role_id', [2, 3])
                ->whereNull('deleted_at')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->count();

            $trendActive[] = User::whereIn('role_id', [2, 3])
                ->whereNull('deleted_at')
                ->where('created_at', '<=', $endOfMonth)
                ->whereNotNull('subscription_end_date')
                ->where('subscription_end_date', '>=', $endOfMonth)
                ->count();

            $trendExpired[] = User::whereIn('role_id', [2, 3])
                ->whereNull('deleted_at')
                ->where('created_at', '<=', $endOfMonth)
                ->where(function ($q) use ($endOfMonth) {
                    $q->whereNull('subscription_end_date')
                      ->orWhere('subscription_end_date', '<', $endOfMonth);
                })
                ->count();
        }

        return response()->json([
            'labels'       => $monthLabels,
            'monthlyData'  => $monthlyData,
            'trendActive'  => $trendActive,
            'trendExpired' => $trendExpired,
        ]);
    }
}
