<?php

namespace App\Http\Controllers;

use App\Models\CompanyLogo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class LogoFaviconController extends Controller
{
    public function index(): View
    {
        
        return view('user.settings.logo-favicon', [
            'pageTitle' => 'Logo & Favicon',
        ]);
    }

    public function getData(Request $request)
    {
        $userId=auth()->user()->id;

        if ($request->ajax()) {
            $query = CompanyLogo::select('company_logos.*','users.name as user_name')
                    ->leftJoin('users','users.id','=','company_logos.user_id')
                    ->where('user_id', $userId)
                    ->orderBy('id', 'DESC');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('image_col', function ($row) {
                    if ($row->logo_image) {
                        return '<img src="' . asset('uploads/' . $row->logo_image) . '" style="max-height:50px;max-width:120px;object-fit:contain;border:1px solid #e5e7eb;border-radius:4px;padding:2px;background:#fff;">';
                    }
                    return '<div style="width:50px;height:50px;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;color:#9ca3af;">No Img</div>';
                })
                ->addColumn('type_col', function ($row) {
                    if ($row->type === 'favicon') {
                        return '<span style="padding:2px 10px;font-size:11px;font-weight:600;border-radius:9999px;background:#fce7f3;color:#9d174d;">Favicon</span>';
                    }
                    return '<span style="padding:2px 10px;font-size:11px;font-weight:600;border-radius:9999px;background:#dbeafe;color:#1e40af;">Logo</span>';
                })
                ->addColumn('status_col', function ($row) {
                    if ($row->is_active == 1) {
                        return '<span style="padding:2px 10px;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Active</span>';
                    }
                    return '<span style="padding:2px 10px;font-size:11px;font-weight:600;border-radius:9999px;background:#f3f4f6;color:#6b7280;">Inactive</span>';
                })

                ->addColumn('createdby', function ($row) {
                    return $row->user_name;
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })

                ->addColumn('action', function ($row) {
                    return '
                        <div class="flex items-center gap-2">
                            <button class="text-blue-600 hover:text-blue-900" title="Edit" onclick="editLogo(' . $row->id . ')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button class="text-red-600 hover:text-red-900" title="Delete" onclick="deleteLogo(' . $row->id . ')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['image_col', 'type_col', 'status_col', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'       => 'nullable|string|max:255',
                'type'       => 'required|in:logo,favicon',
                'logo_image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
                'is_active'  => 'nullable',
            ]);

            $userId = auth()->user()->id;

            $imagePath = null;
            if ($request->file('logo_image')) {
                $imagePath = Storage::disk('local')->putFile('logos', $request->file('logo_image'), 'public');
            }

            // If set as active, deactivate others of same type for this user
            if ($request->has('is_active')) {
                CompanyLogo::where('user_id', $userId)
                    ->where('type', $request->type)
                    ->update(['is_active' => 0]);
            }

            CompanyLogo::create([
                'user_id'    => $userId,
                'name'       => $request->name,
                'type'       => $request->type,
                'logo_image' => $imagePath,
                'is_active'  => $request->has('is_active') ? 1 : 0,
                'created_by' => $userId,
            ]);

            return response()->json([
                'success' => true,
                'message' => ucfirst($request->type) . ' added successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        try {

            $userId=auth()->user()->id;
            $logo = CompanyLogo::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'logo'    => $logo,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logo not found.',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $userId=auth()->user()->id;

            $logo = CompanyLogo::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $request->validate([
                'name'       => 'nullable|string|max:255',
                'type'       => 'required|in:logo,favicon',
                'logo_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
                'is_active'  => 'nullable',
            ]);


            // Handle file upload if provided
            if ($request->hasFile('logo_image')) {
                // Delete old file
                $oldPath = public_path('uploads/logos/' . $logo->logo_image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }

                $imagePath = null;
                if ($request->file('logo_image')) {
                    $imagePath = Storage::disk('local')->putFile('logos', $request->file('logo_image'), 'public');
                }

                $logo->logo_image = $imagePath;
            }

            // If set as active, deactivate others of same type
            if ($request->has('is_active')) {
                CompanyLogo::where('user_id', $userId)
                    ->where('type', $request->type)
                    ->where('id', '!=', $id)
                    ->update(['is_active' => 0]);
                $logo->is_active = 1;
            } else {
                $logo->is_active = 0;
            }

            $logo->name = $request->name;
            $logo->type = $request->type;
            $logo->save();

            return response()->json([
                'success' => true,
                'message' => ucfirst($request->type) . ' updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            
            $userId=auth()->user()->id;

            $logo = CompanyLogo::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            // Delete file
            $path = public_path('uploads/logos/' . $logo->logo_image);
            if (File::exists($path)) {
                File::delete($path);
            }

            $logo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete.',
            ], 500);
        }
    }
}
