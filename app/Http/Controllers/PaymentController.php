<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsExport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function index(): View
    {
        return view('admin.payments.index', [
            'pageTitle' => 'Payments',
        ]);
    }

    public function getTotal(Request $request)
    {
        $query = DB::table('payment_history')->where('status', 'success');

        if ($request->filled('filter_status')) {
            $query = DB::table('payment_history')->where('status', $request->filter_status);
        }
        if ($request->filled('filter_date_from')) {
            $query->whereDate('created_at', '>=', $request->filter_date_from);
        }
        if ($request->filled('filter_date_to')) {
            $query->whereDate('created_at', '<=', $request->filter_date_to);
        }

        return response()->json([
            'total' => $query->sum('amount'),
        ]);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('payment_history')
                ->leftJoin('users', 'payment_history.user_id', '=', 'users.id')
                ->select(
                    'payment_history.*',
                    'users.name as user_name',
                    'users.email as user_email',
                    'users.unique_id as user_unique_id',
                    'users.country_code as user_country_code',
                    'users.mobile as user_mobile'
                )
                ->orderBy('payment_history.id', 'DESC');

            // Status filter
            if ($request->filled('filter_status')) {
                $query->where('payment_history.status', $request->filter_status);
            }

            // Date range filter
            if ($request->filled('filter_date_from')) {
                $query->whereDate('payment_history.created_at', '>=', $request->filter_date_from);
            }
            if ($request->filled('filter_date_to')) {
                $query->whereDate('payment_history.created_at', '<=', $request->filter_date_to);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_col', function ($row) {
                    $name = $row->user_name ?? '—';
                    return $name;
                })
                ->addColumn('mobile_col', function ($row) {
                    $mobile = $row->user_mobile ? $row->user_country_code . ' ' . $row->user_mobile:"--";
                    return $mobile;
                })

                ->addColumn('amount_fmt', function ($row) {
                    return '₹' . number_format($row->amount, 2);
                })
                ->addColumn('scratch_fmt', function ($row) {
                    return number_format($row->scratch_count);
                })
                ->addColumn('status_col', function ($row) {
                    if ($row->status === 'success') {
                        return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Success</span>';
                    } elseif ($row->status === 'failed') {
                        return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#fee2e2;color:#991b1b;">Failed</span>';
                    }
                    return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#fef3c7;color:#92400e;">Pending</span>';
                })
                ->addColumn('date_col', function ($row) {
                    return $row->created_at ? date('d-m-Y h:i A', strtotime($row->created_at)) : '--';
                })
                ->filterColumn('user_col', function ($query, $keyword) {
                    $query->where('users.name', 'like', "%{$keyword}%")
                          ->orWhere('users.email', 'like', "%{$keyword}%");
                })
                ->filterColumn('razorpay_payment_id', function ($query, $keyword) {
                    $query->where('payment_history.razorpay_payment_id', 'like', "%{$keyword}%");
                })
                ->rawColumns(['user_col', 'status_col'])
                ->make(true);
        }
    }

    public function export(Request $request)
    {
        $filename = 'payments_' . date('Y-m-d_His') . '.csv';

        return Excel::download(
            new PaymentsExport(
                $request->filter_status,
                $request->filter_date_from,
                $request->filter_date_to
            ),
            $filename,
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
