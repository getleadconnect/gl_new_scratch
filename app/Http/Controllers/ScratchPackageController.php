<?php

namespace App\Http\Controllers;

use App\Models\ScratchPackage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ScratchPackageController extends Controller
{
    public function index(): View
    {
        return view('admin.scratch-rate.index', [
            'pageTitle' => 'Scratch Rate',
        ]);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = ScratchPackage::orderBy('scratch_count', 'ASC');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('scratch_count_fmt', function ($row) {
                    return number_format($row->scratch_count);
                })
                ->addColumn('rate_fmt', function ($row) {
                    return '₹' . number_format($row->rate, 2);
                })
                ->addColumn('total_amount_fmt', function ($row) {
                    return '₹' . number_format($row->total_amount, 2);
                })

                ->addColumn('created_at', function ($row) {
                    return ($row->created_at)?$row->created_at->format('Y-m-d h:i:s A'):"--";
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="flex items-center gap-2">
                            <button class=" text-blue-600 hover:text-blue-900" title="Edit" onclick="editPackage(' . $row->id . ')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button class=" text-red-600 hover:text-red-900" title="Delete" onclick="deletePackage(' . $row->id . ')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    ';
                })
                ->addColumn('rate', function ($row) {
                    return $row->rate;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'scratch_count' => 'required|integer|min:1|unique:scratch_packages,scratch_count',
                'rate'          => 'required|numeric|min:0',
            ]);

            $total = $request->scratch_count * $request->rate;

            ScratchPackage::create([
                'scratch_count' => $request->scratch_count,
                'rate'          => $request->rate,
                'total_amount'  => $total,
            ]);

            return response()->json(['success' => true, 'message' => 'Package created successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create package: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $pkg = ScratchPackage::findOrFail($id);
            return response()->json(['success' => true, 'package' => $pkg]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Package not found.'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pkg = ScratchPackage::findOrFail($id);

            $request->validate([
                'scratch_count' => 'required|integer|min:1|unique:scratch_packages,scratch_count,' . $id,
                'rate'          => 'required|numeric|min:0',
            ]);

            $total = $request->scratch_count * $request->rate;

            $pkg->update([
                'scratch_count' => $request->scratch_count,
                'rate'          => $request->rate,
                'total_amount'  => $total,
            ]);

            return response()->json(['success' => true, 'message' => 'Package updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update package: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            ScratchPackage::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Package deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete package.'], 500);
        }
    }
}
