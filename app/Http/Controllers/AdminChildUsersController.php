<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Settings;
use App\Models\ScratchCount;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AdminChildUsersController extends Controller
{
    /**
     * Display the child users list page for admin (role_id 1).
     * Shows users with role_id 3 and parent_id = admin's id.
     */
    public function index(): View
    {
        return view('admin.child-users.index', [
            'pageTitle' => 'Users',
        ]);
    }

    /**
     * Get child users data for DataTables (server-side).
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $adminId = auth()->user()->id;
            $today   = now()->toDateString();

            $query = User::where('role_id', 3)
                ->where('parent_id', $adminId)
                ->whereNull('deleted_at');

            if ($request->filled('filter_status')) {
                if ($request->filter_status === 'active') {
                    $query->where('status', 1)
                          ->whereNotNull('subscription_end_date')
                          ->where('subscription_end_date', '>=', $today);
                } elseif ($request->filter_status === 'expired') {
                    $query->where(function ($q) use ($today) {
                        $q->whereNull('subscription_end_date')
                          ->orWhere('subscription_end_date', '<', $today);
                    });
                } elseif ($request->filter_status === 'inactive') {
                    $query->where('status', 0);
                }
            }

            if ($request->filled('filter_date_from')) {
                $query->whereDate('created_at', '>=', $request->filter_date_from);
            }
            if ($request->filled('filter_date_to')) {
                $query->whereDate('created_at', '<=', $request->filter_date_to);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    return $user->name;
                })
                ->addColumn('mobile', function ($user) {
                    return $user->country_code . ' ' . $user->mobile;
                })
                ->addColumn('created_date', function ($user) {
                    return $user->created_at ? $user->created_at->format('d-m-Y') : '--';
                })
                ->addColumn('status', function ($user) {
                    if ($user->status == 1) {
                        $status = '<span style="color:green">Active</span>';
                    } else {
                        $status = '<span style="color:red;">Inactive</span>';
                    }
                    if ($user->subscription_end_date) {
                        $subDate = Carbon::create($user->subscription_end_date)->addDays(1)->format('Y-m-d');
                        if ($subDate <= date('Y-m-d')) {
                            $status = '<span style="color:red;">Expired</span>';
                        }
                    }
                    return $status;
                })
                ->addColumn('subscription', function ($user) {
                    if (!$user->subscription_start_date && !$user->subscription_end_date) {
                        return '--';
                    }
                    $dt = Carbon::parse($user->subscription_start_date)->format('d-m-Y');
                    if ($user->subscription_end_date) {
                        $subDate = Carbon::create($user->subscription_end_date)->addDays(1)->format('Y-m-d');
                        if ($subDate <= date('Y-m-d')) {
                            $dt .= ' => <span style="color:red">' . Carbon::parse($user->subscription_end_date)->format('d-m-Y') . '</span>';
                        } else {
                            $dt .= ' => <span>' . Carbon::parse($user->subscription_end_date)->format('d-m-Y') . '</span>';
                        }
                    }
                    return $dt;
                })
                ->addColumn('action', function ($user) {
                    return '
                        <div class="flex items-center gap-2">
                            <button class="text-blue-600 hover:text-blue-900" title="Edit" onclick="editUser(' . $user->id . ')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button class="text-red-600 hover:text-red-900" title="Delete" onclick="deleteUser(' . $user->id . ')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    ';
                })
                ->filterColumn('name', function ($q, $keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                ->filterColumn('email', function ($q, $keyword) {
                    $q->where('email', 'like', "%{$keyword}%");
                })
                ->filterColumn('unique_id', function ($q, $keyword) {
                    $q->where('unique_id', 'like', "%{$keyword}%");
                })
                ->filterColumn('company_name', function ($q, $keyword) {
                    $q->where('company_name', 'like', "%{$keyword}%");
                })
                ->rawColumns(['name', 'action', 'status', 'subscription'])
                ->make(true);
        }
    }

    /**
     * Store a new child user.
     */
    public function store(Request $request)
    {
        try {
            $adminId = auth()->user()->id;

            $request->validate([
                'name'                    => 'required|string|max:255',
                'email'                   => 'required|email|unique:users,email',
                'country_code'            => 'required|string|max:10',
                'mobile'                  => 'required|string|max:20',
                'company_name'            => 'nullable|string|max:255',
                'address'                 => 'nullable|string',
                'password'                => 'required|string|min:6',
                'subscription_start_date' => 'nullable|date',
                'subscription_end_date'   => 'nullable|date|after_or_equal:subscription_start_date',
            ]);

            $userMobile   = $request->country_code . $request->mobile;
            $existingUser = User::where('user_mobile', $userMobile)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobile number already exists.',
                    'errors'  => ['mobile' => ['This mobile number is already registered.']],
                ], 422);
            }

            $user = User::create([
                'name'                    => $request->name,
                'email'                   => $request->email,
                'country_code'            => $request->country_code,
                'mobile'                  => $request->mobile,
                'user_mobile'             => $userMobile,
                'company_name'            => $request->company_name,
                'address'                 => $request->address,
                'role_id'                 => 3,
                'parent_id'               => $adminId,
                'password'                => bcrypt($request->password),
                'subscription_start_date' => $request->subscription_start_date,
                'subscription_end_date'   => $request->subscription_end_date,
                'status'                  => 1,
            ]);

            $le      = strlen($user->id);
            $uniq_id = 'DS' . str_pad('0', (8 - $le), '0') . $user->id;
            $user->update(['unique_id' => $uniq_id]);

            Settings::create([
                'settings_type'  => 'scratch_otp_enabled',
                'settings_value' => 'Enabled',
                'user_id'        => $user->id,
                'status'         => 1,
            ]);

            return response()->json(['success' => true, 'message' => 'User created successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get child user data for editing.
     */
    public function edit($id)
    {
        $adminId = auth()->user()->id;
        $user    = User::where('id', $id)->where('role_id', 3)->where('parent_id', $adminId)->firstOrFail();
        return response()->json(['success' => true, 'user' => $user]);
    }

    /**
     * Update a child user.
     */
    public function update(Request $request, $id)
    {
        try {
            $adminId = auth()->user()->id;
            $user    = User::where('id', $id)->where('role_id', 3)->where('parent_id', $adminId)->firstOrFail();

            $rules = [
                'name'                    => 'required|string|max:255',
                'email'                   => 'required|email|unique:users,email,' . $id,
                'country_code'            => 'required|string|max:10',
                'mobile'                  => 'required|string|max:20',
                'company_name'            => 'nullable|string|max:255',
                'address'                 => 'nullable|string',
                'subscription_start_date' => 'nullable|date',
                'subscription_end_date'   => 'nullable|date|after_or_equal:subscription_start_date',
            ];
            if ($request->filled('password')) {
                $rules['password'] = 'string|min:6';
            }
            $request->validate($rules);

            $userMobile   = $request->country_code . $request->mobile;
            $existingUser = User::where('user_mobile', $userMobile)->where('id', '!=', $id)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobile number already exists.',
                    'errors'  => ['mobile' => ['This mobile number is already registered to another user.']],
                ], 422);
            }

            $user->name                    = $request->name;
            $user->email                   = $request->email;
            $user->country_code            = $request->country_code;
            $user->mobile                  = $request->mobile;
            $user->user_mobile             = $userMobile;
            $user->company_name            = $request->company_name;
            $user->address                 = $request->address;
            $user->subscription_start_date = $request->subscription_start_date;
            $user->subscription_end_date   = $request->subscription_end_date;
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            return response()->json(['success' => true, 'message' => 'User updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a child user.
     */
    public function destroy($id)
    {
        try {
            $adminId = auth()->user()->id;
            $user    = User::where('id', $id)->where('role_id', 3)->where('parent_id', $adminId)->firstOrFail();
            $user->delete();
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete user.'], 500);
        }
    }
}
