<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignGift;
use App\Models\ScratchCount;
use App\Models\ScratchCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use DB;
use Yajra\DataTables\Facades\DataTables;

class GiftsListController extends Controller
{
    /**
     * Show the Gifts List page.
     */
    public function index(): View
    {
        $campaigns = Campaign::where('user_id', auth()->user()->id)
            ->orderBy('campaign_name')
            ->get(['id', 'campaign_name']);

        return view('user.gifts-list.index', [
            'pageTitle' => 'Gifts List',
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Server-side DataTable data.
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $userId = auth()->user()->id;

            $query = CampaignGift::with('campaign')
                ->where('user_id', $userId)
                ->orderBy('id', 'DESC');

            if ($request->campaign_id) {
                $query->where('campaign_id', $request->campaign_id);
            }

            if ($request->status !== null && $request->status !== '') {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
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
                ->addColumn('action', function ($gift) {
                    $toggleColor = $gift->status == 1 ? '#16a34a' : '#9ca3af';
                    $toggleTitle = $gift->status == 1 ? 'Set Inactive' : 'Set Active';
                    $toggleIcon  = $gift->status == 1
                        ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>'
                        : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

                    return '
                        <div style="display:flex;gap:10px;align-items:center;">
                            <span onclick="editGiftItem(' . $gift->id . ')" title="Edit" style="cursor:pointer;color:#374151;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                </svg>
                            </span>
                            <span onclick="deleteGiftItem(' . $gift->id . ')" title="Delete" style="cursor:pointer;color:#dc2626;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6m4-6v6"/><path d="M9 6V4h6v2"/>
                                </svg>
                            </span>
                            <span onclick="toggleGiftStatus(' . $gift->id . ', ' . $gift->status . ')" title="' . $toggleTitle . '" style="cursor:pointer;color:' . $toggleColor . ';">
                                ' . $toggleIcon . '
                            </span>
                        </div>';
                })
                ->rawColumns(['image_col', 'win_loss_col', 'status_col', 'action'])
                ->make(true);
        }
    }

    /**
     * Get gift data for edit modal (JSON).
     */
    public function edit(int $id)
    {
        $gift = CampaignGift::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $gift->id,
                'description' => $gift->description,
                'status'      => $gift->status,
                'image_url'   => $gift->gift_image ? asset('uploads/' . $gift->gift_image) : null,
            ],
        ]);
    }

    /**
     * Update gift description, image, and status.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'description' => 'required|string',
            'status'      => 'required|in:0,1',
            'image'       => 'nullable|mimes:jpeg,png,jpg,gif|max:51200',
        ]);

        $gift = CampaignGift::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $gift->description = $request->description;
        $gift->status      = $request->status;

        if ($request->hasFile('image')) {
            if ($gift->gift_image) {
                Storage::disk('local')->delete($gift->gift_image);
            }
            $gift->gift_image = Storage::disk('local')->putFile('gifts', $request->file('image'), 'public');
        }

        $gift->save();

        return response()->json(['success' => true, 'message' => 'Gift updated successfully.']);
    }

    /**
     * Toggle gift status (Active ↔ Inactive).
     */
    public function toggleStatus(int $id)
    {
        $gift = CampaignGift::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $gift->status = $gift->status == 1 ? 0 : 1;
        $gift->save();

        $label = $gift->status == 1 ? 'Active' : 'Inactive';

        return response()->json(['success' => true, 'message' => 'Status changed to ' . $label . '.']);
    }

    /**
     * Delete a gift (restores scratch count balance).
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $userId = auth()->user()->id;

            $gift = CampaignGift::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $scratched = ScratchCustomer::where('campaign_gift_id', $id)->count();
            if ($scratched > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Customers have already scratched this gift. It cannot be deleted.",
                ], 422);
            }

            $balanceToRestore = $gift->balance_count;

            if ($gift->gift_image) {
                Storage::disk('local')->delete($gift->gift_image);
            }

            $gift->delete();

            $sc = ScratchCount::where('user_id', $userId)->first();
            if ($sc) {
                $sc->used_count    = $sc->used_count - $balanceToRestore;
                $sc->balance_count = $sc->balance_count + $balanceToRestore;
                $sc->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Gift deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed to delete gift.'], 500);
        }
    }
}
