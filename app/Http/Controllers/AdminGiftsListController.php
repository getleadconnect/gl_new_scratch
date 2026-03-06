<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignGift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class AdminGiftsListController extends Controller
{
    /**
     * Display the gifts list page for admin (role_id 1).
     * Shows gifts belonging to child users (role_id 3, parent_id = admin's id).
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

        return view('admin.gifts-list.index', [
            'pageTitle'  => 'Gifts List',
            'childUsers' => $childUsers,
            'campaigns'  => $campaigns,
        ]);
    }

    /**
     * Get gifts data for DataTables (server-side).
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $adminId = auth()->user()->id;

            $childIds = User::where('role_id', 3)
                ->where('parent_id', $adminId)
                ->pluck('id');

            $query = CampaignGift::with(['campaign', 'user'])
                ->whereIn('user_id', $childIds)
                ->orderBy('id', 'DESC');

            if ($request->filled('filter_user_id')) {
                $query->where('user_id', $request->filter_user_id);
            }

            if ($request->filled('filter_campaign_id')) {
                $query->where('campaign_id', $request->filter_campaign_id);
            }

            if ($request->filled('filter_status')) {
                $query->where('status', $request->filter_status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_name', function ($gift) {
                    return $gift->user ? $gift->user->name : '—';
                })
                ->addColumn('campaign_name', function ($gift) {
                    return $gift->campaign ? $gift->campaign->campaign_name : '—';
                })
                ->addColumn('image_col', function ($gift) {
                    if ($gift->gift_image) {
                        return '<img src="' . asset('uploads/' . $gift->gift_image) . '" alt="Gift"
                                style="width:44px;height:44px;object-fit:cover;border-radius:6px;border:1px solid #e5e7eb;">';
                    }
                    return '<div style="width:44px;height:44px;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;color:#9ca3af;">No Img</div>';
                })
                ->addColumn('win_loss_col', function ($gift) {
                    if ($gift->winning_status == 1) {
                        return '<svg width="20" height="20" viewBox="0 0 24 24" fill="#f59e0b" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                    }
                    return '<span style="color:#9ca3af;">—</span>';
                })
                ->addColumn('status_col', function ($gift) {
                    if ($gift->status == 1) {
                        return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Active</span>';
                    }
                    return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#fee2e2;color:#991b1b;">Inactive</span>';
                })
                ->rawColumns(['image_col', 'win_loss_col', 'status_col'])
                ->make(true);
        }
    }
}
