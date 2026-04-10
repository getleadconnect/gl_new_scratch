<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Models\Campaign;
use App\Models\Gift;
use App\Models\CampaignGift;
use App\Models\ScratchCount;
use App\Models\ScratchCustomer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Validator;
use Log;
use DB;

use Yajra\DataTables\Facades\DataTables;

class GiftsController extends Controller
{
    /**
     * Display the Add Gifts page for a campaign.
     */
    public function show($campaign_id): View
    {
        $campaign = Campaign::where('id', $campaign_id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $scratchCount = ScratchCount::getBalanceScratchCount(auth()->user()->id) ?? 0;

        $type = $campaign->type_id == 1 ? 'Scratch & Win' : 'Other';

        return view('user.campaigns.gifts', [
            'pageTitle'    => 'Add Gifts',
            'campaign'     => $campaign,
            'type'         => $type,
            'scratchCount' => $scratchCount,
        ]);
    }

    /**
     * Get gifts data for DataTables.
     */
    public function getGiftsData(Request $request, $campaign_id)
    {
        if ($request->ajax()) {
            $campaign = Campaign::where('id', $campaign_id)
                ->where('user_id', auth()->user()->id)
                ->firstOrFail();

            $gifts = CampaignGift::where('campaign_id', $campaign_id)
                ->orderBy('id', 'DESC')
                ->get();

            $type = $campaign->type_id == 1 ? 'Scratch & Win' : 'Other';

            return DataTables::of($gifts)
                ->addIndexColumn()
                ->addColumn('image', function ($gift) {
                    if ($gift->gift_image) {
                        return '<img src="' . asset('uploads/' . $gift->gift_image) . '" alt="Gift" class="h-10 w-10 object-cover rounded border border-gray-200">';
                    }
                    return '<div class="h-10 w-10 bg-gray-100 border border-gray-200 rounded flex items-center justify-center text-xs text-gray-400">No Image</div>';
                })
                ->addColumn('stage', function ($gift) use ($type) {
                    return $type;
                })
                ->addColumn('win_loss', function ($gift) {
                    if ($gift->winning_status == 1) {
                        return '<svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                    }
                    return '<span class="text-gray-400">—</span>';
                })
                ->addColumn('status', function ($gift) {
                    if ($gift->status == 1) {
                        return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Active</span>';
                    }
                    return '<span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#fee2e2;color:#991b1b;">Inactive</span>';
                })
                ->addColumn('action', function ($gift) {
                    return '
                        <div class="flex items-center gap-2">
                            <button class="text-blue-600 hover:text-blue-900" title="Edit" onclick="editGift(' . $gift->id . ')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button class="text-red-600 hover:text-red-900" title="Delete" onclick="deleteGift(' . $gift->id . ')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['image', 'win_loss', 'status', 'action'])
                ->make(true);
        }
    }

    /**
     * Store a new gift.
     */
    public function store(Request $request, $campaign_id)
    {

        $validate=Validator::make($request->all(),
			[
                'gift_count'  => 'required|integer|min:1',
                'description' => 'required|string',
                'status'      => 'required|in:1,0',
                'image'       => 'required|mimes:jpeg,png,jpg,gif|max:51200', // 500mb = 512000kb but keeping reasonable
		    ]);

		if($validate->fails())
		{
			return response()->json(['success' => false, 'message' => $validate->messages()->first()], 422);
		}

        try {

            DB::beginTransaction();

            $user_id=auth()->user()->id;
            $sc=ScratchCount::where('user_id',$user_id)->first();  //update scratch count

            if($sc->balance_count<=0 or $sc->balance_count<$request->gift_count)
            {
                return response()->json(['success' => false, 'message' => 'Insufficiant scratch credits..!'], 422);
            }
           
            $campaign = Campaign::where('id', $campaign_id)
                ->where('user_id', $user_id)
                ->firstOrFail();

            $imagePath = null;

            if($request->file('image'))
			{ 
				$imagePath=Storage::disk('local')->putFile("gifts",$request->file('image'), 'public');
				//$fname1=str_replace("banner_images/","",$fname1);
			}

            CampaignGift::create([
                'campaign_id' => $campaign_id,
                'gift_count'  => $request->gift_count,
                'description' => $request->description,
                'user_id'     => auth()->user()->id,
                'type_id'     => $campaign->type_id,
                'balance_count'=>$request->gift_count,
                'status'      => $request->status,
                'gift_image'       => $imagePath,
                'winning_status'  => 1,
            ]);

              //update scratch count
                $gift_count=$request->gift_count;
				$ucount=$sc->total_count-($sc->used_count+$request->gift_count);
				
				$sc->used_count=$sc->used_count+$gift_count;
				$sc->balance_count=$sc->balance_count-$gift_count;
				$sc->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Gift added successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed to add gift: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get gift data for editing.
     */
    public function edit($campaign_id, $id)
    {
        try {
            $gift = CampaignGift::where('id', $id)->where('campaign_id', $campaign_id)->firstOrFail();
            return response()->json(['success' => true, 'gift' => $gift]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gift not found.'], 404);
        }
    }

    /**
     * Update a gift.
     */

    public function update(Request $request, $campaign_id, $id)
    {
    
            $validate = Validator::make($request->all(), [
                'gift_count'  => 'required|integer|min:1',
                'description' => 'required|string',
                'status'      => 'required|in:1,0',
                'image'       => 'nullable|mimes:jpeg,png,jpg,gif|max:51200',
            ]);

            if ($validate->fails()) {
                return response()->json(['success' => false, 'message' => $validate->messages()->first()], 422);
            }

            $user_id=auth()->user()->id;
            $gift_id=$request->editgiftId;
            
            $gift=CampaignGift::where('id',$gift_id)->first();

            $old_cnt=$gift->gift_count;
            $dif=$old_cnt-$request->gift_count;

            $sc=ScratchCount::where('user_id',$user_id)->first();  //update scratch count
            
            if(abs($dif)>$sc->balance_count)
            {
                return response()->json(['success' => false, 'message' => 'Insufficient scratch credits!'], 422);
            }

            $customer_count=ScratchCustomer::where('user_id',$user_id)->where('campaign_id',$campaign_id)->count();
            
            if($customer_count>$request->gift_count)	
            {
                return response()->json(['success' => false, 'message' => "Already (".$customer_count .") customers scratch this offer, Can't reduce count.!"], 422);
            }


        DB::beginTransaction();

        try {

            if($customer_count<$request->gift_count)
				{
					$gift->gift_count=$request->gift_count;
					$gift->balance_count=$request->gift_count-$customer_count;
				}

            $imagePath = $gift->gift_image;

            if ($request->file('image')) {
                $old_file = $gift->gift_image;
                $imagePath = Storage::disk('local')->putFile('gifts', $request->file('image'), 'public');
                
                if ($old_file) {
                    Storage::disk('local')->delete($old_file);
                }
            }

            if($request->has('status'))
				$gift->winning_status=$request->status;
			
                $gift->description=$request->description;
				$gift->gift_image=$imagePath;  
				$gift->save();
								
				$sc->used_count=$sc->used_count-$dif;
				$sc->balance_count=$sc->balance_count+$dif;
				$sc->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Gift updated successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed to update gift: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a gift.
     */
    public function destroy($campaign_id, $id)
    {
        
        DB::beginTransaction();
        try {
            $gift = CampaignGift::where('id', $id)->where('campaign_id', $campaign_id)->firstOrFail();

            $cnt=ScratchCustomer::where('campaign_gift_id',$id)->count();
			if($cnt>0)
			{
				return response()->json(['success'=>false, 'message' => "Customer already scratched, Can't remove this gift!."],402);
			}	

            if ($gift) 
			{
                $user_id=auth()->user()->id;
				
				$scount=$gift->balance_count;
				
                if($gift->gift_image!=null)
                 Storage::disk('local')->delete($gift->gift_image);
                				
				$gift->delete();
								
				$sc=ScratchCount::where('user_id',$user_id)->first();  //update scratch count
				$sc->used_count=($sc->used_count-$scount);
				$sc->balance_count=($sc->balance_count+$scount);
				$sc->save();

                DB::commit();
            }
            
            return response()->json(['success' => true, 'message' => 'Gift deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Failed to delete gift.'], 500);
        }
    }
}
