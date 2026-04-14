<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PurchaseScratchHistory;
use App\Exports\PurchaseHistoryExport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PurchaseHistoryController extends Controller
{
    /**
     * Display the purchase history list page.
     */
    public function index(): View
    {
        $users = User::whereIn('role_id', [2, 3])
            ->whereNull('deleted_at')
            ->orderBy('name', 'ASC')
            ->get();

        $defaultFrom = Carbon::now()->subMonths(3)->toDateString();
        $defaultTo   = Carbon::now()->toDateString();

        return view('admin.purchase-history.index', [
            'pageTitle'   => 'Purchase History',
            'users'       => $users,
            'defaultFrom' => $defaultFrom,
            'defaultTo'   => $defaultTo,
        ]);
    }

    /**
     * Get purchase history data for DataTables (server-side).
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $defaultFrom = Carbon::now()->subMonths(3)->toDateString();
            $defaultTo   = Carbon::now()->toDateString();

            $dateFrom = $request->filled('filter_date_from') ? $request->filter_date_from : $defaultFrom;
            $dateTo   = $request->filled('filter_date_to')   ? $request->filter_date_to   : $defaultTo;

            $query = PurchaseScratchHistory::select(
                    'purchase_scratch_history.*',
                    'users.name as user_name',
                    'users.unique_id as user_unique_id',
                    'users.role_id as user_role_id',
                    'users.country_code as user_country_code',
                    'users.mobile as user_mobile'
                )
                ->join('users', 'purchase_scratch_history.user_id', '=', 'users.id')
                ->whereIn('users.role_id', [2, 3])
                ->whereNull('users.deleted_at')
                ->whereDate('purchase_scratch_history.created_at', '>=', $dateFrom)
                ->whereDate('purchase_scratch_history.created_at', '<=', $dateTo);

            if ($request->filled('filter_user_id')) {
                $query->where('purchase_scratch_history.user_id', $request->filter_user_id);
            }

            $query->orderBy('purchase_scratch_history.created_at', 'DESC');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('purchase_date', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y h:i A') : '--';
                })
                ->addColumn('user_name', function ($row) {
                    $url = route('admin.users.show', $row->user_id);
                    return '<a href="'.$url.'" class="text-blue-600 hover:text-blue-900 hover:underline font-medium">'.strtoupper($row->user_name).'</a>';
                })
                ->addColumn('user_unique_id', function ($row) {
                    return $row->user_unique_id ?? '--';
                })
                ->addColumn('mobile', function ($row) {
                    return $row->user_country_code . ' ' . $row->user_mobile;
                })
                ->addColumn('role', function ($row) {
                    return $row->user_role_id == 2 ? 'User' : 'Child';
                })
                ->addColumn('narration', function ($row) {
                    return $row->narration;
                })
                ->addColumn('scratch_count', function ($row) {
                    return '<span style="color:#22c55e;font-weight:600;">' . number_format($row->scratch_count) . '</span>';
                })
                ->addColumn('amount', function ($row) {
                    $amt = (float) ($row->amount ?? 0);
                    return '<div style="text-align:right;font-weight:600;">&#8377; ' . number_format($amt, 2) . '</div>';
                })
                ->rawColumns(['user_name', 'scratch_count', 'amount'])
                ->make(true);
        }
    }

    /**
     * Export purchase history to CSV.
     */
    public function export(Request $request)
    {
        $defaultFrom = Carbon::now()->subMonths(3)->toDateString();
        $defaultTo   = Carbon::now()->toDateString();

        $dateFrom = $request->filled('filter_date_from') ? $request->filter_date_from : $defaultFrom;
        $dateTo   = $request->filled('filter_date_to')   ? $request->filter_date_to   : $defaultTo;
        $userId   = $request->filled('filter_user_id')   ? (int) $request->filter_user_id : null;

        $filename = 'purchase-history_' . date('Y-m-d_His') . '.csv';
        return Excel::download(new PurchaseHistoryExport($userId, $dateFrom, $dateTo), $filename, \Maatwebsite\Excel\Excel::CSV);
    }
}
