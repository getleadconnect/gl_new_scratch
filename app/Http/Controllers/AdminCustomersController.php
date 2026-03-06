<?php

namespace App\Http\Controllers;

use App\Exports\AdminCustomersExport;
use App\Models\Campaign;
use App\Models\Branch;
use App\Models\ScratchCustomer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AdminCustomersController extends Controller
{
    /**
     * Display the customers list page for admin (role_id 1).
     * Shows scratched customers belonging to child users' campaigns.
     */
    public function index(): View
    {
        $adminId = auth()->user()->id;

        $childIds = User::where('role_id', 3)
            ->where('parent_id', $adminId)
            ->pluck('id');

        $childUsers = User::where('role_id', 3)
            ->where('parent_id', $adminId)
            ->orderBy('name')
            ->get(['id', 'name']);

        $campaigns = Campaign::whereIn('user_id', $childIds)
            ->whereNull('deleted_at')
            ->orderBy('campaign_name')
            ->get(['id', 'campaign_name']);

        return view('admin.customers.index', [
            'pageTitle'  => 'Customers List',
            'childUsers' => $childUsers,
            'campaigns'  => $campaigns,
        ]);
    }

    /**
     * Export customers to Excel.
     */
    public function export(Request $request)
    {
        $adminId  = auth()->user()->id;
        $childIds = User::where('role_id', 3)
            ->where('parent_id', $adminId)
            ->pluck('id')
            ->toArray();

        $filename = 'customers_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new AdminCustomersExport(
                childIds:     $childIds,
                filterUserId: $request->input('filter_user_id'),
                campaignId:   $request->input('campaign_id'),
                winStatus:    $request->input('win_status'),
                redeemStatus: $request->input('redeem_status'),
                dateFrom:     $request->input('date_from'),
                dateTo:       $request->input('date_to')
            ),
            $filename
        );
    }

    /**
     * Get customers data for DataTables (server-side).
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $adminId = auth()->user()->id;

            $childIds = User::where('role_id', 3)
                ->where('parent_id', $adminId)
                ->pluck('id');

            $query = ScratchCustomer::with(['campaign', 'branches', 'user'])
                ->whereIn('user_id', $childIds);

            if ($request->filled('filter_user_id')) {
                $query->where('user_id', $request->filter_user_id);
            }

            if ($request->filled('campaign_id')) {
                $query->where('campaign_id', $request->campaign_id);
            }

            if ($request->filled('win_status')) {
                $query->where('win_status', $request->win_status);
            }

            if ($request->filled('redeem_status')) {
                $query->where('redeem', $request->redeem_status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->date_from == null && $request->date_to == null) {
                $sdate = Carbon::parse(date('Y-m-d'))->subMonths(3)->format('Y-m-d');
                $edate = Carbon::now()->format('Y-m-d');
                $query->whereDate('created_at', '>=', $sdate)
                      ->whereDate('created_at', '<=', $edate);
            }

            return DataTables::of($query->orderBy('id', 'DESC'))
                ->addIndexColumn()
                ->filterColumn('customer_name', function ($q, $keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                ->filterColumn('customer_mobile', function ($q, $keyword) {
                    $q->where(function ($sq) use ($keyword) {
                        $sq->where('cust_mobile', 'like', "%{$keyword}%")
                           ->orWhere('mobile', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('unique_id', function ($q, $keyword) {
                    $q->where('unique_id', 'like', "%{$keyword}%");
                })
                ->filterColumn('campaign_name', function ($q, $keyword) {
                    $q->whereHas('campaign', function ($cq) use ($keyword) {
                        $cq->where('campaign_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('branch_name', function ($q, $keyword) {
                    $q->whereHas('branches', function ($bq) use ($keyword) {
                        $bq->where('branch_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('offer', function ($q, $keyword) {
                    $q->where('offer_text', 'like', "%{$keyword}%");
                })
                ->filterColumn('bill_no', function ($q, $keyword) {
                    $q->where('bill_no', 'like', "%{$keyword}%");
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user ? $row->user->name : '--';
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->name ?? '--';
                })
                ->addColumn('customer_mobile', function ($row) {
                    return $row->cust_mobile ?? $row->mobile ?? '--';
                })
                ->addColumn('unique_id', function ($row) {
                    return $row->unique_id ?? '--';
                })
                ->addColumn('campaign_name', function ($row) {
                    return $row->campaign ? $row->campaign->campaign_name : '--';
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->branches ? $row->branches->branch_name : '--';
                })
                ->addColumn('offer', function ($row) {
                    return $row->offer_text ?? '--';
                })
                ->addColumn('bill_no', function ($row) {
                    return $row->bill_no ?? '--';
                })
                ->addColumn('redeemed', function ($row) {
                    if ($row->redeem == 1 && $row->win_status == 1)
                        return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Yes</span>';
                    elseif ($row->redeem == 0 && $row->win_status == 1)
                        return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#fee2e2;color:#991b1b;">No</span>';
                    else
                        return '--';
                })
                ->addColumn('win_status', function ($row) {
                    if ($row->win_status == 1) {
                        return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#fef9c3;color:#854d0e;">Win</span>';
                    }
                    return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#f3f4f6;color:#4b5563;">Loss</span>';
                })
                ->addColumn('created_date', function ($row) {
                    return $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y H:i A') : '--';
                })
                ->rawColumns(['win_status', 'redeemed'])
                ->make(true);
        }
    }
}
