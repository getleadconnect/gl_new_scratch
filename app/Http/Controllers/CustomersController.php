<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Models\Campaign;
use App\Models\Branch;
use App\Models\ScratchCustomer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class CustomersController extends Controller
{
    /**
     * Display the customers list page.
     */
    public function index(): View
    {
        $userId = auth()->user()->id;
        $campaigns = Campaign::where('user_id', $userId)->orderBy('campaign_name')->get();
        $branches = Branch::where('user_id', $userId)->orderBy('branch_name')->get();
        return view('user.customers.index', [
            'pageTitle' => 'Customers',
            'campaigns' => $campaigns,
            'branches' =>$branches,
        ]);
    }

    /**
     * Export customers to CSV.
     */
    public function export(Request $request)
    {
        $userId   = auth()->user()->id;
        $filename = 'customers_' . now()->format('Ymd_His') . '.csv';

        return Excel::download(
            new CustomersExport(
                userId:     $userId,
                campaignId: $request->input('campaign_id'),
                winStatus:  $request->input('win_status'),
                redeemStatus:  $request->input('redeem_status'),
                dateFrom:   $request->input('date_from'),
                dateTo:     $request->input('date_to')
            ),
            $filename,
            \Maatwebsite\Excel\Excel::CSV,
            ['Content-Type' => 'text/csv']
        );
    }

    /**
     * Get customers data for DataTables.
     */
    public function getCustomersData(Request $request)
    {

        if ($request->ajax()) {
            $userId = auth()->user()->id;

            $query = ScratchCustomer::with('campaign','branches')
                ->where('user_id', $userId);

            if ($request->filled('campaign_id')) {
                $query->where('campaign_id', $request->campaign_id);
            }

            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
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

            if($request->date_from==null &&  $request->date_to==null)  //3 months data
            {
                $sdate=Carbon::parse(date('Y-m-d'))->subMonths(3)->format('Y-m-d');
                $edate=Carbon::now()->format('Y-m-d');

                $query->whereDate('created_at','>=',$sdate)
					   ->whereDate('created_at','<=',$edate);
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
                ->addColumn('short_code', function ($row) {
                    return $row->short_code ?? '--';
                })
                ->addColumn('bill_no', function ($row) {
                    return $row->bill_no ?? '--';
                })
                ->addColumn('redeemed', function ($row) {
                    if($row->redeem==1 and $row->win_status==1)
                        return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Yes</span>';
                    elseif($row->redeem==0 and $row->win_status==1)
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
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Active</span>';
                    }
                    return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#fee2e2;color:#991b1b;">Inactive</span>';
                })
                ->addColumn('created_date', function ($row) {
                    return $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y H:i A') : '--';
                })
                ->rawColumns(['win_status', 'status','redeemed'])
                ->make(true);
        }
    }
}
