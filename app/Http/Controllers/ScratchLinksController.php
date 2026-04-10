<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignGift;
use App\Models\ScratchLink;
use App\Models\LinkCountSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

use Yajra\DataTables\Facades\DataTables;

use App\Traits\EndroidQrcodeTrait;
use Barryvdh\DomPDF\Facade\Pdf;

class ScratchLinksController extends Controller
{
    use EndroidQrcodeTrait;

    /**
     * Display the scratch links page.
     */
    public function index(): View
    {
        $userId    = auth()->user()->id;
        $campaigns = Campaign::where('user_id', $userId)->orderBy('campaign_name')->get();

        return view('user.scratch-links.index', [
            'pageTitle' => 'Scratch Links',
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Store a new scratch link.
     */
    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'short_code'  => 'required|min:5|unique:scratch_links,short_code',
        ]);

        $userId = auth()->user()->id;

        $giftCount=CampaignGift::where('campaign_id',$request->campaign_id)->where('status',1)->count();

		if($giftCount<=0)
		{
		    return response()->json(['success' => false, 'message' => 'The selected campaign did not add gifts!'], 404);	
		}

        $slink=ScratchLink::where('user_id',$userId)->where('short_code',strtoupper($request->short_code))->first();
		if($slink)
		{
			return response()->json(['success' => false, 'message' => 'Short code already exists.!'], 404);	
		}
		else
		{
            try
            {
                $short_code=$request->short_code;
                $shortLink = $request->short_link.$short_code;

                $fileName="qr_codes/".$short_code.'-'.time().'.png';
                $path = public_path('uploads/'.$fileName);

                //$shortLink=env('SHORT_LINK_DOMAIN') . '/'.$user_id."/". $short_code;
                $qrResult=$this->generateQrCode($shortLink,$path);  

                ScratchLink::create([
                    'user_id'              => $userId,
                    'campaign_id'          => $request->campaign_id,
                    'short_code'           => $request->short_code,
                    'link'                 => $shortLink,
                    'qrcode_file'          => $fileName,
                    'bill_number_required' => $request->bill_number_required ?? 0,
                    'branch_required'      => $request->branch_required ?? 0,
                    'email_required'       => $request->email_required ?? 0,
                    'link_type'            => "Single",
                    'status'               => 1,
                    'click_count'          => 0,
                ]);

                return response()->json(['success' => true, 'message' => 'Link created successfully.']);
            }
            catch(\Exception $e)
            {
                return response()->json(['success' => false, 'message' =>$e->getMessage()]);
            }

        }
    }


    /**
     * Generate multiple scratch links at once.
     */
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'link_count'  => 'required|integer|min:1|max:1000',
        ]);

        $userId = auth()->user()->id;

        $giftCount = CampaignGift::where('campaign_id', $request->campaign_id)->where('status', 1)->count();

        if ($giftCount <= 0) {
            return response()->json(['success' => false, 'message' => 'The selected campaign did not add gifts!'], 404);
        }

        $linkCount      = (int) $request->link_count;
        $baseUrl        = $request->short_link;
        $branchRequired = $request->branch_required ?? 0;
        $created        = 0;

        try {

                $link_cat=$request->link_count." links (".date('d-m-Y-h-i-s').")";
				$linkSection=LinkCountSection::create([
                        'campaign_id'=>$request->campaign_id,
                        'user_id'=>$userId,
                        'section_name'=>$link_cat
                        ]);
				$linkSectionId=$linkSection->id;

            for ($i = 0; $i < $linkCount; $i++) {
                // Generate a unique 8-char uppercase short code
                do {
                    $shortCode = strtoupper(Str::random(8));
                } while (ScratchLink::where('short_code', $shortCode)->exists());

                $fullLink = $baseUrl . $shortCode;
                $fileName = "qr_codes/" . $shortCode . '-' . time() . $i . '.png';
                $path     = public_path('uploads/' . $fileName);

                $this->generateQrCode($fullLink, $path);

                ScratchLink::create([
                    'user_id'         => $userId,
                    'campaign_id'     => $request->campaign_id,
                    'short_code'      => $shortCode,
                    'link'            => $fullLink,
                    'qrcode_file'     => $fileName,
                    'branch_required' => $branchRequired,
                    'link_count_section_id'=>$linkSectionId,
                    'link_type'       =>"Multiple",
                    'status'          => 1,
                    'click_count'     => 0,
                ]);

                $created++;
            }

            return response()->json(['success' => true, 'message' => $created . ' links generated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get a scratch link for editing.
     */
    public function edit(int $id)
    {
        $userId = auth()->user()->id;
        $link   = ScratchLink::where('id', $id)->where('user_id', $userId)->first();

        if (!$link) {
            return response()->json(['success' => false, 'message' => 'Link not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => $link]);
    }

    /**
     * Update a scratch link.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
        ]);

        $userId = auth()->user()->id;
        $link   = ScratchLink::where('id', $id)->where('user_id', $userId)->first();

        if (!$link) {
            return response()->json(['success' => false, 'message' => 'Link not found.'], 404);
        }

        try {
            $link->update([
                'campaign_id'          => $request->campaign_id,
                'bill_number_required' => $request->bill_number_required ?? 0,
                'branch_required'      => $request->branch_required ?? 0,
                'email_required'       => $request->email_required ?? 0,
            ]);

            return response()->json(['success' => true, 'message' => 'Link updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Toggle the status of a scratch link (Active <-> Inactive).
     */
    public function toggleStatus(int $id)
    {
        $userId = auth()->user()->id;
        $link   = ScratchLink::where('id', $id)->where('user_id', $userId)->first();

        if (!$link) {
            return response()->json(['success' => false, 'message' => 'Link not found.'], 404);
        }

        $link->status = $link->status == 1 ? 0 : 1;
        $link->save();

        $label = $link->status == 1 ? 'Active' : 'Inactive';

        return response()->json(['success' => true, 'message' => 'Status changed to ' . $label . '.', 'status' => $link->status]);
    }

    /**
     * Delete a scratch link.
     */
    public function destroy(Request $request, int $id)
    {
        $userId = auth()->user()->id;
        try
        {
            $link   = ScratchLink::where('id', $id)->where('user_id', $userId)->first();
            if (!$link) {
                return response()->json(['success' => false, 'message' => 'Link not found.'], 404);
            }

            if($link->qrcode_file!=null ) {
                Storage::disk('local')->delete($link->qrcode_file);
            }

            $link->delete();

            return response()->json(['success' => true, 'message' => 'Link deleted successfully.']);
        }
        catch(\Exception $e)
        {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Return link count sections for a given campaign (used by QR PDF modal).
     */
    public function getLinkSections(Request $request)
    {
        $userId     = auth()->user()->id;
        $campaignId = $request->campaign_id;

        $sections = LinkCountSection::where('user_id', $userId)
            ->where('campaign_id', $campaignId)
            ->orderBy('id', 'DESC')
            ->get(['id', 'section_name']);

        return response()->json(['success' => true, 'data' => $sections]);
    }

    /**
     * Download a PDF of QR codes for a link count section.
     */
    public function downloadQrPdf(Request $request)
    {
        $request->validate([
            'section_id' => 'required|integer|exists:link_count_section,id',
        ]);

        $userId  = auth()->user()->id;
        $section = LinkCountSection::where('id', $request->section_id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $links = ScratchLink::where('link_count_section_id', $section->id)
            ->where('user_id', $userId)
            ->get();

        // Embed QR images as base64 so dompdf can render them
        foreach ($links as $link) {
            $imgPath = public_path('uploads/' . $link->qrcode_file);
            if ($link->qrcode_file && file_exists($imgPath)) {
                $link->qr_base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath));
            } else {
                $link->qr_base64 = null;
            }
        }

        $pdf = Pdf::loadView('user.scratch-links.qr-pdf', [
            'section' => $section,
            'links'   => $links,
        ])->setPaper('a4', 'portrait');

        $filename = 'qrcodes-' . $section->id . '-' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get scratch links data for DataTables.
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $userId = auth()->user()->id;

            $query = ScratchLink::with(['campaign'])
                ->where('user_id', $userId);

            if ($request->filled('campaign_id')) {
                $query->where('campaign_id', $request->campaign_id);
            }

            if ($request->filled('link_type')) {
                $query->where('link_type', $request->link_type);
            }

            return DataTables::of($query->orderBy('id', 'DESC'))
                ->addIndexColumn()
                ->addColumn('offer_name', function ($row) {
                    if ($row->campaign) {
                        return $row->campaign->campaign_name ?? '--';
                    }
                    return $row->name ?? '--';
                })

                ->addColumn('link_type', function ($row) {
                
                if ($row->link_type == "Multiple") {
                        return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">M</span>';
                    }
                    return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#fee2e2;color:#991b1b;">S</span>';
                })

                ->addColumn('link_url', function ($row) {
                    $url = $row->link ?? $row->url ?? '--';
                    if ($url !== '--') {
                        return '<a href="' . e($url) . '" target="_blank" style="color:#2563eb;">' . e($url) . '</a>';
                    }
                    return '--';
                })
                ->addColumn('qrcode_col', function ($row) {
                    if ($row->qrcode_file) {
                        $imgUrl = asset('uploads/' . $row->qrcode_file);
                        return '
                            <div style="display:flex;align-items:center;gap:6px;">
                                <a href="' . $imgUrl . '" target="_blank" title="View QR Code" class="act-button">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="1.5">
                                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                                        <rect x="5" y="5" width="3" height="3" fill="#374151"/><rect x="16" y="5" width="3" height="3" fill="#374151"/>
                                        <rect x="5" y="16" width="3" height="3" fill="#374151"/>
                                        <rect x="14" y="14" width="2" height="2" fill="#374151"/><rect x="17" y="14" width="4" height="2" fill="#374151"/>
                                        <rect x="14" y="17" width="2" height="4" fill="#374151"/>
                                    </svg>
                                </a>

                                <a href="' . $imgUrl . '" download title="Download QR Code" class="act-button">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                </a>
                            </div>';
                    }
                    return '--';
                })
                ->addColumn('email_req', function ($row) {
                    return $row->email_required ? 'Yes' : 'No';
                })
                ->addColumn('billno_req', function ($row) {
                    return $row->bill_number_required ? 'Yes' : 'No';
                })
                ->addColumn('shop_req', function ($row) {
                    return $row->branch_required ? 'Yes' : 'No';
                })
                ->addColumn('status_col', function ($row) {
                    if ($row->status == 1) {
                        return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Active</span>';
                    }
                    return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#fee2e2;color:#991b1b;">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $toggleColor = $row->status == 1 ?  '#5c95f8': '#16a34a' ;
                    $toggleTitle = $row->status == 1 ? 'Set Inactive' : 'Set Active';
                    return '
                        <div style="display:flex;gap:10px;align-items:center;">
                            <span  onclick="editLink(' . $row->id . ')" title="Edit" style="cursor:pointer;color:#374151;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                </svg>
                            </span>
                            <span  onclick="deleteLink(' . $row->id . ')" title="Delete" style="cursor:pointer;color:#dc2626;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6m4-6v6"/><path d="M9 6V4h6v2"/>
                                </svg>
                            </span>
                            <span  onclick="toggleStatus(' . $row->id . ', ' . $row->status . ')" title="' . $toggleTitle . '" style="cursor:pointer;color:' . $toggleColor . ';">
                                ' . ($row->status == 1
                                    ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>'
                                    : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>'
                                ) . '
                            </span>
                        </div>';
                })
                ->rawColumns(['link_url', 'qrcode_col', 'status_col', 'action','link_type'])
                ->make(true);
        }
    }
}
