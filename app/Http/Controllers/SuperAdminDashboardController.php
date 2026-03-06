<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ScratchCount;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        return view('admin.dashboard', [
            'pageTitle'    => 'Dashboard',
            'totalUsers'   => $totalUsers,
            'activeUsers'  => $activeUsers,
            'expiredUsers' => $expiredUsers,
            'totalScratch' => $totalScratch,
        ]);
    }
}
