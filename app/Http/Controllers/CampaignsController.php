<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignGift;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Validator;

class CampaignsController extends Controller
{
    /**
     * Display the campaigns list page.
     */
    public function index(): View
    {
        return view('user.campaigns.index', [
            'pageTitle' => 'Campaigns Management',
        ]);
    }

    /**
     * Get campaigns data for DataTables.
     */
    public function getCampaignsData(Request $request)
    {
        $userId = auth()->user()->id;

        if ($request->ajax()) {
            $query = Campaign::where('user_id', $userId)->whereNull('deleted_at');

            // Status filter
            if ($request->filled('filter_status')) {
                $query->where('status', $request->filter_status);
            }

            // Date range filter (end_date)
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
                ->addColumn('add_gift', function ($campaign) {
                    $url = route('user.campaigns.gifts.show', $campaign->id);
                    return '
                        <div class="flex items-center gap-2">
                            <a href="' . $url . '" class="flex items-center px-2 py-1 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Gift
                            </a>
                        </div>
                    ';
                })
                ->addColumn('action', function ($campaign) {
                    return '
                        <div class="flex items-center gap-2">
                            <button class="text-blue-600 hover:text-blue-900" title="Edit" onclick="editCampaign(' . $campaign->id . ')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button class="text-red-600 hover:text-red-900" title="Delete" onclick="deleteCampaign(' . $campaign->id . ')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['campaign_image', 'action', 'status', 'type', 'add_gift'])
                ->make(true);
        }
    }

    /**
     * Store a new campaign.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'campaign_name' => 'required|string|max:255',
            'end_date'      => 'required|date',
            'status'        => 'required|in:1,0',
            'campaign_image'=> 'nullable|mimes:jpeg,png,jpg,gif|max:51200',
        ]);

        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->messages()->first()], 422);
        }

        try {
            $imagePath = null;
            if ($request->file('campaign_image')) {
                $imagePath = Storage::disk('local')->putFile('campaigns', $request->file('campaign_image'), 'public');
            }

            Campaign::create([
                'campaign_name'  => $request->campaign_name,
                'user_id'        => auth()->user()->id,
                'end_date'       => $request->end_date,
                'status'         => $request->status,
                'type_id'        => 1,
                'campaign_image' => $imagePath,
            ]);

            return response()->json(['success' => true, 'message' => 'Campaign created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create campaign: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get campaign data for editing.
     */
    public function edit($id)
    {
        try {
            $campaign = Campaign::where('user_id', auth()->user()->id)->findOrFail($id);
            return response()->json(['success' => true, 'campaign' => $campaign]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Campaign not found.'], 404);
        }
    }

    /**
     * Update a campaign.
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'campaign_name' => 'required|string|max:255',
            'end_date'      => 'required|date',
            'status'        => 'required|in:1,0',
            'campaign_image'=> 'nullable|mimes:jpeg,png,jpg,gif|max:51200',
        ]);

        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->messages()->first()], 422);
        }

        try {
            $campaign = Campaign::where('user_id', auth()->user()->id)->findOrFail($id);

            $imagePath = $campaign->campaign_image;
            if ($request->file('campaign_image')) {
                $old = $campaign->campaign_image;
                $imagePath = Storage::disk('local')->putFile('campaigns', $request->file('campaign_image'), 'public');
                if ($old) {
                    Storage::disk('local')->delete($old);
                }
            }

            $campaign->update([
                'campaign_name'  => $request->campaign_name,
                'end_date'       => $request->end_date,
                'status'         => $request->status,
                'campaign_image' => $imagePath,
            ]);

            return response()->json(['success' => true, 'message' => 'Campaign updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update campaign: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a campaign.
     */
    public function destroy($id)
    {
        try {

            $user_id=auth()->user()->id;
            $campaign = Campaign::where('user_id', $user_id)->findOrFail($id);
                
            $gifts=CampaignGift::where('campaign_id',$campaign->id)->get();
            if($gifts)
                {
                    foreach($gifts as $row)
                    {
                        
                        if($row->gift_image!=null)
                            Storage::disk('local')->delete($row->gift_image);
                        $row->delete();
                    }
               }

            if ($campaign->campaign_image) {
                Storage::disk('local')->delete($campaign->campaign_image);
            }

            $campaign->delete();

            return response()->json(['success' => true, 'message' => 'Campaign deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete campaign.'], 500);
        }

    }
}
