<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AdminCampaignsController extends Controller
{
    /**
     * Display the campaigns list page for admin (role_id 1).
     * Shows campaigns belonging to child users (role_id 3, parent_id = admin's id).
     */
    public function index(): View
    {
        $adminId = auth()->user()->id;

        $childUsers = User::where('role_id', 3)
            ->where('parent_id', $adminId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.campaigns.index', [
            'pageTitle'   => 'Campaigns',
            'childUsers'  => $childUsers,
        ]);
    }

    /**
     * Get campaigns data for DataTables (server-side).
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $adminId = auth()->user()->id;

            // Get child user IDs belonging to this admin
            $childIds = User::where('role_id', 3)
                ->where('parent_id', $adminId)
                ->pluck('id');

            $query = Campaign::with('user')
                ->whereIn('user_id', $childIds)
                ->whereNull('deleted_at');

            // Filter by child user
            if ($request->filled('filter_user_id')) {
                $query->where('user_id', $request->filter_user_id);
            }

            // Filter by status
            if ($request->filled('filter_status')) {
                $query->where('status', $request->filter_status);
            }

            // Filter by end_date range
            if ($request->filled('filter_date_from')) {
                $query->whereDate('end_date', '>=', $request->filter_date_from);
            }
            if ($request->filled('filter_date_to')) {
                $query->whereDate('end_date', '<=', $request->filter_date_to);
            }

            return DataTables::of($query->orderBy('id', 'DESC'))
                ->addIndexColumn()
                ->filterColumn('campaign_name', function ($q, $keyword) {
                    $q->where('campaign_name', 'like', "%{$keyword}%");
                })
                ->filterColumn('user_name', function ($q, $keyword) {
                    $q->whereHas('user', function ($uq) use ($keyword) {
                        $uq->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('user_name', function ($campaign) {
                    return $campaign->user ? $campaign->user->name : '--';
                })
                ->addColumn('campaign_name', function ($campaign) {
                    return $campaign->campaign_name;
                })
                ->addColumn('campaign_image', function ($campaign) {
                    if ($campaign->campaign_image) {
                        return '<img src="' . asset('uploads/' . $campaign->campaign_image) . '" alt="Campaign" class="h-10 w-10 object-cover rounded border border-gray-200">';
                    }
                    return '<div class="h-10 w-10 bg-gray-100 border border-gray-200 rounded flex items-center justify-center text-xs text-gray-400">No Img</div>';
                })
                ->addColumn('type', function ($campaign) {
                    if ($campaign->type_id == 1)
                        return '<span class="px-2 inline-flex text-xs leading-5 rounded-full bg-light-cyan">Scratch Card</span>';
                    return '--';
                })
                ->addColumn('end_date', function ($campaign) {
                    return Carbon::parse($campaign->end_date)->format('d-m-Y');
                })
                ->addColumn('status', function ($campaign) {
                    if ($campaign->status == 1) {
                        return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Active</span>';
                    }
                    return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#f3f4f6;color:#991b1b;">Inactive</span>';
                })
                ->rawColumns(['campaign_image', 'status', 'type'])
                ->make(true);
        }
    }
}
