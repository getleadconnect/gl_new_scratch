<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Imports\BranchImport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BranchesController extends Controller
{
    /**
     * Show the branches page.
     */
    public function index(): View
    {
        return view('user.settings.branches', [
            'pageTitle' => 'Branches',
        ]);
    }

    /**
     * Server-side DataTable data.
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = Branch::where('user_id', auth()->user()->id)
                ->orderBy('id', 'DESC');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status_col', function ($branch) {
                    if ($branch->status == 1) {
                        return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Active</span>';
                    }
                    return '<span style="padding:2px 10px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#fee2e2;color:#991b1b;">Inactive</span>';
                })
                ->addColumn('action', function ($branch) {
                    $toggleColor = $branch->status == 1 ? '#16a34a' : '#9ca3af';
                    $toggleTitle = $branch->status == 1 ? 'Set Inactive' : 'Set Active';
                    $toggleIcon  = $branch->status == 1
                        ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>'
                        : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

                    return '
                        <div style="display:flex;gap:10px;align-items:center;">
                            <span onclick="editBranch(' . $branch->id . ',
                                \'' . addslashes($branch->branch_name) . '\',
                                ' . $branch->status . ')"
                                title="Edit" style="cursor:pointer;color:#2563eb;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                </svg>
                            </span>
                            <span onclick="deleteBranch(' . $branch->id . ')" title="Delete" style="cursor:pointer;color:#dc2626;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6m4-6v6"/><path d="M9 6V4h6v2"/>
                                </svg>
                            </span>
                            <span onclick="toggleBranchStatus(' . $branch->id . ', ' . $branch->status . ')" title="' . $toggleTitle . '" style="cursor:pointer;color:' . $toggleColor . ';">
                                ' . $toggleIcon . '
                            </span>
                        </div>';
                })
                ->rawColumns(['status_col', 'action'])
                ->make(true);
        }
    }

    /**
     * Store a new branch.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'status'      => 'required|in:0,1',
        ]);

        Branch::create([
            'user_id'     => auth()->user()->id,
            'branch_name' => $request->branch_name,
            'status'      => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Branch added successfully.']);
    }

    /**
     * Update a branch.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'status'      => 'required|in:0,1',
        ]);

        $branch = Branch::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $branch->update([
            'branch_name' => $request->branch_name,
            'status'      => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Branch updated successfully.']);
    }

    /**
     * Toggle branch status.
     */
    public function toggleStatus(int $id)
    {
        $branch = Branch::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $branch->status = $branch->status == 1 ? 0 : 1;
        $branch->save();

        $label = $branch->status == 1 ? 'Active' : 'Inactive';

        return response()->json(['success' => true, 'message' => 'Status changed to ' . $label . '.']);
    }

    /**
     * Import branches from an Excel / CSV file.
     * Expected columns: branch_name (required), status (optional: Active/Inactive/1/0)
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new BranchImport(auth()->user()->id), $request->file('import_file'));
            return response()->json(['success' => true, 'message' => 'Branches imported successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Import failed: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Delete a branch.
     */
    public function destroy(int $id)
    {
        $branch = Branch::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $branch->delete();

        return response()->json(['success' => true, 'message' => 'Branch deleted successfully.']);
    }
}
