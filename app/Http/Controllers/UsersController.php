<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Models\Settings;
use App\Models\PurchaseScratchHistory;
use App\Models\ScratchCount;
use App\Models\ScratchPackage;
use Validator;
use DB;

use Carbon\Carbon;

class UsersController extends Controller
{
    
    /**
     * Display the users list page.
     */
    public function index(): View
    {
    $parent_users=User::where('role_id',1)->where('status',1)->get();

    return view('admin.users.index', [
            'pageTitle' => 'Users Management',
            'parent_users'=>$parent_users
        ]);
    }

    /**
     * Get users data for DataTables.
     */
    public function getUsersData(Request $request)
    {
        if ($request->ajax()) {
            $today = now()->toDateString();

            $query = User::select('users.*', 'parent.unique_id as parent_unique_id')
                ->leftJoin('users as parent', 'users.parent_id', '=', 'parent.id')
                ->where('users.role_id', '!=', 0)
                ->whereNull('users.deleted_at');

            // Role filter
            if ($request->filled('filter_role')) {
                $query->where('users.role_id', $request->filter_role);
            }

            // Status filter
            if ($request->filled('filter_status')) {
                if ($request->filter_status === 'active') {
                    $query->where('users.status', 1)
                          ->whereNotNull('users.subscription_end_date')
                          ->where('users.subscription_end_date', '>=', $today);
                } elseif ($request->filter_status === 'expired') {
                    $query->where(function($q) use ($today) {
                        $q->whereNull('users.subscription_end_date')
                          ->orWhere('users.subscription_end_date', '<', $today);
                    });
                } elseif ($request->filter_status === 'inactive') {
                    $query->where('users.status', 0);
                }
            }

            // Date range filter (created_at)
            if ($request->filled('filter_date_from')) {
                $query->whereDate('users.created_at', '>=', $request->filter_date_from);
            }
            if ($request->filled('filter_date_to')) {
                $query->whereDate('users.created_at', '<=', $request->filter_date_to);
            }

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('name', function ($user) {
                    $url = route('admin.users.show', $user->id);
                    return '<a href="'.$url.'" class="text-blue-600 hover:text-blue-900 hover:underline font-medium">'.strtoupper($user->name).'</a>';
                         //.'<br><span style="font-size:13px;color:#6b7280;">'.$user->email.'</span>';
                })

                ->addColumn('created_date', function ($user) {
                    return $user->created_at ? $user->created_at->format('d-m-Y') : '--';
                })

                ->addColumn('mobile', function ($user) {
                    return $user->country_code." ".$user->mobile;
                })

                ->addColumn('company_name', function ($user) {
                    $company = $user->company_name ?: '--';
                    $address = $user->address ? '<br><span style="font-size:13px;color:#6b7280;">' . $user->address . '</span>' : '';
                    return $company . $address;
                })

                ->addColumn('role', function ($user) {
                    if($user->role_id==0)
                        return "Superadmin";
                    elseif($user->role_id==1)
                        return "Admin";
                    elseif($user->role_id==2)
                        return "User";
                    else
                        return "Child";
                })

                ->editColumn('parent_id', function ($user) {
                    
                    if($user->role_id==1)
                    {    
                        $url = route('admin.sub-users.index', $user->id);
                        return '<a href="'.$url.'" id="btn-apply-filter"
                            class="py-1 px-3 text-xs font-small rounded-md text-white"
                            style="background:#18181b;border:none;cursor:pointer;white-space:nowrap;">
                            Child Users
                        </a>';
                    }
                    else
                    {
                        return $user->parent_unique_id ?? '--';
                    }

                })

                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('users.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('email', function ($query, $keyword) {
                    $query->where('users.email', 'like', "%{$keyword}%");
                })
                ->filterColumn('unique_id', function ($query, $keyword) {
                    $query->where('users.unique_id', 'like', "%{$keyword}%");
                })
                ->filterColumn('company_name', function ($query, $keyword) {
                    $query->where('users.company_name', 'like', "%{$keyword}%");
                })
                ->addColumn('status', function ($user) {
                    if ($user->status==1) {
                            $status='<span style="color:green">Active</span>';
                    } else {
                    $status='<span style="color:red;">Inactive</span>';
                    }
			        $subscription_date = Carbon::create($user->subscription_end_date)->addDays(1)->format('Y-m-d');
			        if($subscription_date<=date('Y-m-d'))
			        {
				        $status='<span style="color:red;">Expired</span>';
			        }
                    
			    return $status;

                })
		
                ->addColumn('subscription', function ($user) {
            
                    $dt=Carbon::parse($user->subscription_start_date)->format('d-m-Y');
                    $subscription_date = Carbon::create($user->subscription_end_date)->addDays(1)->format('Y-m-d');
                    if($subscription_date<=date('Y-m-d'))
                        $dt.=" => "."<span style='color:red'>".Carbon::parse($user->subscription_end_date)->format('d-m-Y')."</span>";
                    else
                        $dt.=" => "."<span>".Carbon::parse($user->subscription_end_date)->format('d-m-Y')."</span>";

                    return $dt;
                })
                
                ->addColumn('action', function ($user) {
                    return '
                        <div class="flex items-center gap-2">
                            <button class=" text-blue-600 hover:text-blue-900" title="Edit" onclick="editUser('.$user->id.')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button class=" text-red-600 hover:text-red-900" title="Delete" onclick="deleteUser('.$user->id.')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['name','company_name','action','status','subscription','parent_id'])
                ->make(true);
        }
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'country_code' => 'required|string|max:10',
                'mobile' => 'required|string|max:20',
                'company_name' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'role' => 'required',
                'password' => 'required|string|min:6',
                'subscription_start_date' => 'required|date',
                'subscription_end_date' => 'required|date|after_or_equal:subscription_start_date',
            ]);

            $validatedData['parent_id']=$request->parent_id??null;
            // Check if user_mobile (country_code + mobile) already exists
            $userMobile = $validatedData['country_code'] . $validatedData['mobile'];
            $existingUser = User::where('user_mobile', $userMobile)->first();


            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobile number already exists.',
                    'errors' => ['mobile' => ['This mobile number is already registered.']]
                ], 422);
            }

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'country_code' => $validatedData['country_code'],
                'mobile' => $validatedData['mobile'],
                'user_mobile' => $userMobile,
                'company_name' => $validatedData['company_name'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'role_id' => $validatedData['role'],
                'parent_id' => $validatedData['parent_id']??null,
                'password' => bcrypt($validatedData['password']),
                'subscription_start_date' => $validatedData['subscription_start_date'],
                'subscription_end_date' => $validatedData['subscription_end_date'],
                'status' => 1,
            ]);

            $user_id = $user->id;

            // Update unique id
            $le = strlen($user_id);
            $uniq_id = "DS" . str_pad("0", (8 - $le), '0') . $user_id;
            User::where('id', $user_id)->update(['unique_id' => $uniq_id]);

            // Create settings
            $sdata = [
                'settings_type' => "otp_enabled",
                'settings_value' => "Enabled",
                'user_id' => $user_id,
                'status' => 1,
            ];

            Settings::create($sdata);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'user' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user data for editing.
     */

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }
    }

    /**
     * Update a user.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validationRules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'country_code' => 'required|string|max:10',
                'mobile' => 'required|string|max:20',
                'company_name' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'role' => 'required',
                //'subscription_start_date' => 'required|date',
                //'subscription_end_date' => 'required|date|after_or_equal:subscription_start_date',
            ];

            // Only validate password if it's provided
            if ($request->filled('password')) {
                $validationRules['password'] = 'string|min:6';
            }

            $validatedData = $request->validate($validationRules);

            $validatedData['parent_id']=$request->parent_id??null;

            // Check if user_mobile (country_code + mobile) already exists for another user
            $userMobile = $validatedData['country_code'] . $validatedData['mobile'];
            $existingUser = User::where('user_mobile', $userMobile)
                ->where('id', '!=', $id)
                ->first();

            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobile number already exists.',
                    'errors' => ['mobile' => ['This mobile number is already registered to another user.']]
                ], 422);
            }

            // Update user data
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->country_code = $validatedData['country_code'];
            $user->mobile = $validatedData['mobile'];
            $user->user_mobile = $userMobile;
            $user->company_name = $validatedData['company_name'] ?? null;
            $user->address = $validatedData['address'] ?? null;
            $user->role_id = $validatedData['role'];
            $user->parent_id = $validatedData['parent_id']??null;
            //$user->subscription_start_date = $validatedData['subscription_start_date'];
            //$user->subscription_end_date = $validatedData['subscription_end_date'];

            // Only update password if provided
            if ($request->filled('password')) {
                $user->password = bcrypt($validatedData['password']);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'user' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user.'
            ], 500);
        }
    }


    /**
     * to get selected admin user subscription period.
     */
    public function getAdminSubscriptionPeriod($id)
    {
        try {
            $user = User::findOrFail($id);
            $period['start_date']=$user->subscription_start_date;
            $period['end_date']=$user->subscription_end_date;

            return response()->json([
                'success' => true,
                'data' => $period,
                'message' => 'subscription dates.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get subscription dates.'
            ], 500);
        }
    }

    /**
     * Display user profile page.
     */
    public function show($id): View
    {
        $user = User::findOrFail($id);

        $scratchCount = ScratchCount::where('user_id', $user->id)->first();
        $childUsersCount = User::where('parent_id', $user->id)->whereNull('deleted_at')->count();
        $scratchPackages = \App\Models\ScratchPackage::orderBy('scratch_count', 'ASC')->get();

        return view('admin.users.show', [
            'pageTitle' => 'User Profile',
            'user' => $user,
            'scratch_count' => $scratchCount->balance_count ?? 0,
            'total_scratch' => $scratchCount->total_count ?? 0,
            'used_scratch' => $scratchCount->used_count ?? 0,
            'balance_scratch' => $scratchCount->balance_count ?? 0,
            'user_role_id'=>$user->role_id,
            'child_users_count' => $childUsersCount,
            'scratchPackages' => $scratchPackages,
        ]);
    }

    /**
     * Add subscription period to user.
     */
    public function addSubscription(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validatedData = $request->validate([
                'subscription_start_date' => 'required|date',
                'subscription_end_date' => 'required|date|after_or_equal:subscription_start_date',
            ]);

            $user->subscription_start_date = $validatedData['subscription_start_date'];
            $user->subscription_end_date = $validatedData['subscription_end_date'];
            $user->save();

            /* --- apply subscription period to child users ---- */
            $childUsers=User::where('parent_id',$user->id)->get();
            if($childUsers)
            {
                foreach($childUsers as $user)
                {
                   $user->subscription_start_date = $validatedData['subscription_start_date'];
                   $user->subscription_end_date = $validatedData['subscription_end_date'];
                   $user->save();
                }
            }
            /*-----------------------*/

            //This is child user, Then update same period as parent(admin) user subscription period.
            if($user->role_id==3)
            {
                  $parentUser=User::where('id',$user->parent_id)->first();
                  $parentUser->subscription_start_date = $validatedData['subscription_start_date'];
                  $parentUser->subscription_end_date = $validatedData['subscription_end_date'];
                  $parentUser->save();
            }
            
        	$sc=ScratchCount::where('user_id',$user->id)->first();
			if($sc)
			{
				$sc->total_count=0;
				$sc->used_count=0;
				$sc->balance_count=0;
				$sc->save();
			}
			else
			{
				$dat=[
				  'user_id'=>$user->id,
				  'total_count'=>0,
				  'used_count'=>0,
				  'balance_count'=>0,
				];
                
				$res=ScratchCount::create($dat);	
			}
		
            return response()->json([
                'success' => true,
                'message' => 'Subscription period updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subscription: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add scratch count to user.
     */
    public function addScratch(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validatedData = $request->validate([
                'scratch_count' => 'required|integer|min:1',
            ]);

            DB::beginTransaction();

				$user_id=$user->id;
				
				$package=ScratchPackage::where('scratch_count',$request->scratch_count)->first();
                $amount=0;
                if($package)
                {
                        $amount=$package->total_amount;
                }

                $sc=PurchaseScratchHistory::where('user_id',$user_id)->latest()->first();
				if($sc)
				{
					$sc->status=0;
					$sc->save();
				}
						
				$data=[
					'user_id'=>$user_id,
					'narration'=>"To purchase ". $request->scratch_count. " scratch credits dated on ".date('d-m-Y'),
					'scratch_count'=>$request->scratch_count,
                    'amount'=>$amount,
					'status'=>1
				];
				
				$result=PurchaseScratchHistory::create($data);
								
				if($result)
        		{   
					$scnt=ScratchCount::where('user_id',$user_id)->first();
					if($scnt)
					{
						$scnt->total_count=$scnt->total_count+$request->scratch_count;
						$scnt->balance_count=$scnt->balance_count+$request->scratch_count;
						$scnt->save();
					}
					else
					{
						
						$dat=[
							'user_id'=>$user_id,
							'total_count'=>$request->scratch_count,
							'balance_count'=>$request->scratch_count,
						];
						$scnt=ScratchCount::create($dat);
					}

                    DB::commit();
                    return response()->json([
                        'success' => true,
                        'message' => 'Scratch credit added successfully.',
                        'scratch_count' => $validatedData['scratch_count']
                    ]);

        		}
        		else
        		{
                    DB::rollback();
                    
                    return response()->json([
                    'success' => false,
                        'message' => 'Failed to add scratch credit: ' . $e->getMessage()
                    ], 500);
        		}

        } catch (\Exception $e) {

             DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add scratch credit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get scratch purchase history for DataTables.
     */
    public function getScratchHistory(Request $request, $id)
    {
        if ($request->ajax()) {
            // For now, returning empty data
            // You can add your scratch history model/table later
            $scratchHistory = PurchaseScratchHistory::where('user_id',$id)->orderBy('created_at','DESC')->get();

            return DataTables::of($scratchHistory)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return $row->created_at->format('d-m-Y H:i');
                })
                ->addColumn('narration', function ($row) {
                    return $row->narration;
                })
                ->addColumn('scratch_count', function ($row) {
                    return $row->scratch_count;
                })
                ->make(true);
        }
    }
}
