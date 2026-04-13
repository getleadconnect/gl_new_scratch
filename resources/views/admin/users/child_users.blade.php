@extends('layouts.admin')

<!-- Country Code Picker CSS -->
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/datatable.css')}}">

<style>
   
   .disabled{
      background-color:#ececec;
   }

    /* Modal Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            transform: scale(0.95) translateY(-10px);
            opacity: 0;
        }
        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    #deleteConfirmModal,
    #editUserModal,
    #addUserModal {
        animation: fadeIn 0.2s ease-out;
    }

    #deleteConfirmModal .animate-in,
    #editUserModal .animate-in,
    #addUserModal .animate-in {
        animation: slideIn 0.2s ease-out;
    }

    /* International Telephone Input Styling */
    .iti {
        width: 100%;
    }

    .iti__flag-container {
        padding: 0;
    }

    .iti__selected-flag {
        padding: 0 8px;
        border-right: 1px solid #e4e4e4;
    }

    #mobileNumber,
    #editMobileNumber {
        padding-left: 52px;
    }

    .iti__country-list {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        max-height: 200px;
    }

    .iti__country:hover {
        background-color: #f3f4f6;
    }

    .iti__country.iti__highlight {
        background-color: #3b82f6;
    }

.s-table tbody tr,td
{
    padding:5px 10px !important;
    font-size:13px !important;
}

</style>
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div id="users-page-header" class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Child Users - {{ $parentName }}</h1>
            <p class="mt-2 text-sm text-muted-foreground">Manage all registered users</p>
        </div>
        <div class="flex items-center gap-2">
            <button id="openAddUserModal" class="flex items-center px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New User
            </button>
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground hover:bg-muted transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg " style="border:1px solid #e4e4e4;">
        <div id="users-filter-fields" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">

        <!-- content here ------->
     <input type="hidden" id="admin_user_id" name="admin_user_id" value="{{$parent_id}}">

     <p class="pl-4 pt-2" style="color:#5050e5;"> *: To apply subscription period and scratch credits to all child users</p>

     <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Add Subscription Period Card -->
               
                <div class="profile-card p-6 pt-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Add Subscription Period</h3>
                    <form id="subscriptionForm">
                        @csrf
                        <div class="space-y-4">
                            <div class="flex gap-3 items-end">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                    <input type="date" name="subscription-start-date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                    <input type="date" name="subscription-end-date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-slate-900 text-white hover:bg-slate-800 h-10 px-4 py-2" style="width: 175px;">
                                    Update Subscription
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Add Scratch Count Card -->
                <div class="profile-card p-6 pt-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Add Scratch Credits</h3>
                    <form id="scratchForm">
                        @csrf
                        <div class="space-y-4">
                            <div class="flex gap-3 items-end">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Scratch Count</label>
                                    <input type="number" name="scratch_count" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter count">
                                </div>
                                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-slate-900 text-white hover:bg-slate-800 h-10 px-4" style="width: 175px;">
                                    Add Scratch
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>

    <!-- DataTable Wrapper -->
    <div id="datatable-wrapper">
        <!-- DataTable Card -->
        <div id="datatable-card" class="bg-white shadow-sm rounded-lg" style="border: 1px solid #e4e4e4;">
            <div class="p-6">
                <table id="users-table" class="data-table w-full" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Unique_ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Company</th>
                            <th>Role</th>
                            <th>Subscription</th>
                            <th>Scratch Credits</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Info and Pagination will be appended here by DataTables (outside card) -->
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 my-8 animate-in">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Add New User</h3>
                <button id="closeAddUserModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <form id="addUserForm" class="p-6">


            <!-- Subscription Start/End Date -->
                    <input type="hidden" name="subscription_start_date" value="{{$admin_user->subscription_start_date}}" >
                    <input type="hidden" name="subscription_end_date" value="{{$admin_user->subscription_end_date}}" >
           
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Mobile with Country Code -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="tel" id="mobileNumber" name="mobile_full" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <input type="hidden" name="country_code" id="countryCode">
                    <input type="hidden" name="mobile" id="mobileOnly">
                </div>

                <!-- Company Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                    <input type="text" name="company_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="role" class=" disabled w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" >
                        <option value="">Select Role</option>
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                        <option value="3" selected>Child</option>
                    </select>
                </div>

                <!-- Parent users -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent User <span class="text-red-500">*</span></label>
                    <select name="parent_user_id" id="parentUserId" class=" disabled w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" >
                        <option value="">Select Parent</option>
                        @if($parent_users)
                            @foreach($parent_users as $row)
                                <option value="{{$row->id}}" @if($row->id==$parent_id) selected @endif>{{$row->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <!-- Company Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
            </div>

            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" id="cancelAddUser" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-slate-900 text-white hover:bg-slate-800 h-10 px-4 py-2">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 my-8 animate-in">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
                <button id="closeEditUserModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <form id="editUserForm" class="p-6">
            <input type="hidden" name="user_id" id="editUserId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="editEmail" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Mobile with Country Code -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="tel" id="editMobileNumber" name="mobile_full" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Company Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                    <input type="text" name="company_name" id="editCompanyName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" id="editAddress" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="editRole" class="disabled w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled>
                        <option value="">Select Role</option>
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                        <option value="3">Child</option>
                    </select>
                </div>

                <!-- Parent users -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent User <span class="text-red-500">*</span></label>
                    <select name="parent_user_id" id="editParentUserId" class="disabled  w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled>
                        <option value="">Select Parent</option>
                        @if($parent_users)
                            @foreach($parent_users as $row)
                                <option value="{{$row->id}}" >{{$row->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Password -->

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-500">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" id="editPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

               {{-- <!-- Subscription Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subscription Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="subscription_start_date" id="editSubscriptionStartDate" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Subscription End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subscription End Date <span class="text-red-500">*</span></label>
                    <input type="date" name="subscription_end_date" id="editSubscriptionEndDate" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                --}}

            </div>

            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" id="cancelEditUser" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-slate-900 text-white hover:bg-slate-800 h-10 px-4 py-2">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 animate-in">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Delete User</h3>
                    <p class="mt-2 text-sm text-gray-500">Are you sure you want to delete this user? This action cannot be undone.</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end rounded-b-lg">
            <button id="cancelDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                Cancel
            </button>
            <button id="confirmDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 py-2">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- Mobile responsive overrides -->
<style>
@media (max-width: 640px) {
    /* Page header — stack title and button */
    #users-page-header {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    #users-page-header > button {
        width: 100%;
        justify-content: center;
    }

    /* Filters — full-width fields and buttons */
    #users-filter-fields {
        flex-direction: column;
        align-items: stretch;
    }
    #users-filter-fields > div {
        width: 100% !important;
    }
    #users-filter-fields select,
    #users-filter-fields input[type="date"] {
        width: 100% !important;
        box-sizing: border-box;
    }
    #users-filter-fields .flex.gap-2 {
        flex-direction: column;
    }
    #btn-apply-filter,
    #btn-reset-filter {
        width: 100%;
    }

    /* DataTable — horizontal scroll */
    #datatable-card {
        overflow-x: auto;
    }
}
</style>

<!-- jQuery (required for DataTables) -->
<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>

<!-- DataTables JS -->
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

<!-- International Telephone Input JS -->
<script src="{{asset('assets/js/intlTelInput.min.js')}}"></script>

<script>

// Declare global variables for intl-tel-input instances
let iti;
let editIti;

$(document).ready(function() {

// Initialize International Telephone Input for Add Modal
const phoneInput = document.querySelector("#mobileNumber");
iti = window.intlTelInput(phoneInput, {
    initialCountry: "in",
    preferredCountries: ["us", "gb", "in", "ae"],
    separateDialCode: true,
    utilsScript: "{{asset('assets/js/intlTelInput_utils.js')}}"

});

// Initialize International Telephone Input for Edit Modal
const editPhoneInput = document.querySelector("#editMobileNumber");
editIti = window.intlTelInput(editPhoneInput, {
    initialCountry: "in",
    preferredCountries: ["us", "gb", "in", "ae"],
    separateDialCode: true,
    utilsScript: "{{asset('assets/js/intlTelInput_utils.js')}}"
});

var table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
		stateSave:true,
		paging     : true,
        pageLength :50,
		/*scrollX: true,*/
		
		'pagingType':"simple_numbers",
        'lengthChange': true,
        language: {
            search: '',
            searchPlaceholder: 'Search users...',
        },
		
        ajax: {
            url: "{{ route('admin.sub-users.data') }}",
            type: 'GET',
            data: function(d) {
                d.parent_id = '{{ $parent_id }}';
            }
        },
        columns: [
            { data: 'DT_RowIndex',   name: 'DT_RowIndex',   orderable: false, searchable: false },
            { data: 'unique_id',     name: 'unique_id',     orderable: false },
            { data: 'name',          name: 'name',          orderable: true },
            { data: 'email',         name: 'email',         orderable: true },
            { data: 'mobile',        name: 'mobile',        orderable: false, searchable: false },
            { data: 'company_name',  name: 'company_name',  orderable: false },
            { data: 'role',          name: 'role',          orderable: false, searchable: false },
            { data: 'subscription',  name: 'subscription',  orderable: false, searchable: false },
            { data: 'scratch_count',  name: 'scratch_count',  orderable: false, searchable: false },
            { data: 'status',        name: 'status',        orderable: false, searchable: false },
            { data: 'created_date',  name: 'created_at',    orderable: true,  searchable: false },
            { data: 'action',        name: 'action',        orderable: false, searchable: false },
        ],
		
});

// Open Add User Modal
$('#openAddUserModal').on('click', function() {
    $('#addUserModal').removeClass('hidden');
});

// Close Add User Modal
$('#closeAddUserModal, #cancelAddUser').on('click', function() {
    $('#addUserModal').addClass('hidden');
    $('#addUserForm')[0].reset();
});

// Close modal on background click
$('#addUserModal').on('click', function(e) {
    if (e.target === this) {
        $(this).addClass('hidden');
        $('#addUserForm')[0].reset();
    }
});

// Handle Add User Form Submission
$('#addUserForm').on('submit', function(e) {
    e.preventDefault();

    // Get country code and mobile number from intl-tel-input
    const selectedCountryData = iti.getSelectedCountryData();
    const fullNumber = iti.getNumber();
    const countryCode = '+' + selectedCountryData.dialCode;
    const nationalNumber = fullNumber.replace(countryCode, '').trim();

    const formData = {
        name: $('input[name="name"]').val(),
        email: $('input[name="email"]').val(),
        country_code: countryCode,
        mobile: nationalNumber,
        company_name: $('input[name="company_name"]').val(),
        address: $('textarea[name="address"]').val(),
        role: $('select[name="role"]').val(),
        parent_id: $('select[name="parent_user_id"]').val(),
        password: $('input[name="password"]').val(),
        subscription_start_date: $('input[name="subscription_start_date"]').val(),
        subscription_end_date: $('input[name="subscription_end_date"]').val(),
        _token: '{{ csrf_token() }}'
    };

    $.ajax({
        url: '{{ route("admin.users.store") }}',
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                // Hide modal
                $('#addUserModal').addClass('hidden');
                // Reset form
                $('#addUserForm')[0].reset();
                // Show success notification
                showNotification('success', response.message);
                // Reload DataTable
                $('#users-table').DataTable().ajax.reload();
            } else {
                showNotification('error', response.message);
            }
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred while creating the user.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMessage = Object.values(errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification('error', errorMessage);
        }
    });
});

// Filter Apply / Reset
$('#btn-apply-filter').on('click', function() {
    table.ajax.reload();
});

$('#btn-reset-filter').on('click', function() {
    $('#filter-role').val('');
    $('#filter-status').val('');
    $('#filter-date-from').val('');
    $('#filter-date-to').val('');
    table.ajax.reload();
});

});

// Edit user function
function editUser(userId) {
    // Fetch user data
    $.ajax({
        url: '{{ route("admin.users.edit", ":id") }}'.replace(':id', userId),
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const user = response.user;

                // Populate form fields
                $('#editUserId').val(user.id);
                $('#editName').val(user.name);
                $('#editEmail').val(user.email);
                $('#editCompanyName').val(user.company_name);
                $('#editAddress').val(user.address);
                $('#editRole').val(user.role_id);
                $('#editParentUserId').val(user.parent_id);
                $('#editSubscriptionStartDate').val(user.subscription_start_date);
                $('#editSubscriptionEndDate').val(user.subscription_end_date);

                // Set phone number with country code
                const fullPhone = user.country_code + user.mobile;
                editIti.setNumber(fullPhone);

                // Show modal
                $('#editUserModal').removeClass('hidden');
            } else {
                showNotification('error', response.message);
            }
        },
        error: function(xhr) {
            showNotification('error', 'Failed to fetch user data.');
        }
    });
}

// Close Edit User Modal
$('#closeEditUserModal, #cancelEditUser').on('click', function() {
    $('#editUserModal').addClass('hidden');
    $('#editUserForm')[0].reset();
});

// Close edit modal on background click
$('#editUserModal').on('click', function(e) {
    if (e.target === this) {
        $(this).addClass('hidden');
        $('#editUserForm')[0].reset();
    }
});

// Handle Edit User Form Submission
$('#editUserForm').on('submit', function(e) {
    e.preventDefault();

    const userId = $('#editUserId').val();

    // Get country code and mobile number from intl-tel-input
    const selectedCountryData = editIti.getSelectedCountryData();
    const fullNumber = editIti.getNumber();
    const countryCode = '+' + selectedCountryData.dialCode;
    const nationalNumber = fullNumber.replace(countryCode, '').trim();

    const formData = {
        name: $('#editName').val(),
        email: $('#editEmail').val(),
        country_code: countryCode,
        mobile: nationalNumber,
        company_name: $('#editCompanyName').val(),
        address: $('#editAddress').val(),
        role: $('#editRole').val(),
        parent_id:$('#editParentUserId').val(),
        password: $('#editPassword').val(),
        subscription_start_date: $('#editSubscriptionStartDate').val(),
        subscription_end_date: $('#editSubscriptionEndDate').val(),
        _token: '{{ csrf_token() }}'
    };

    $.ajax({
        url: '{{ route("admin.users.update", ":id") }}'.replace(':id', userId),
        type: 'PUT',
        data: formData,
        success: function(response) {
            if (response.success) {
                // Hide modal
                $('#editUserModal').addClass('hidden');
                // Reset form
                $('#editUserForm')[0].reset();
                // Show success notification
                showNotification('success', response.message);
                // Reload DataTable
                $('#users-table').DataTable().ajax.reload();
            } else {
                showNotification('error', response.message);
            }
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred while updating the user.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMessage = Object.values(errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification('error', errorMessage);
        }
    });
});

// Delete user function with modal
let deleteUserId = null;

function deleteUser(userId) {
    deleteUserId = userId;
    // Show the modal
    $('#deleteConfirmModal').removeClass('hidden');
}

// Cancel delete
$('#cancelDelete').on('click', function() {
    $('#deleteConfirmModal').addClass('hidden');
    deleteUserId = null;
});

// Close modal on background click
$('#deleteConfirmModal').on('click', function(e) {
    if (e.target === this) {
        $(this).addClass('hidden');
        deleteUserId = null;
    }
});

// Confirm delete
$('#confirmDelete').on('click', function() {
    if (deleteUserId) {
        $.ajax({
            url: '{{ route("admin.users.destroy", ":id") }}'.replace(':id', deleteUserId),
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Hide modal
                $('#deleteConfirmModal').addClass('hidden');

                if (response.success) {
                    // Show success message (you can replace with a toast notification)
                    showNotification('success', response.message);
                    // Reload the DataTable
                    $('#users-table').DataTable().ajax.reload();
                } else {
                    showNotification('error', response.message);
                }
                deleteUserId = null;
            },
            error: function(xhr) {
                // Hide modal
                $('#deleteConfirmModal').addClass('hidden');
                showNotification('error', 'An error occurred while deleting the user.');
                deleteUserId = null;
            }
        });
    }
});


// Simple notification function (you can enhance this with a toast library)
function showNotification(type, message) {
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const notification = $(`
        <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-in">
            ${message}
        </div>
    `);

    $('body').append(notification);

    setTimeout(function() {
        notification.fadeOut(300, function() {
            $(this).remove();
        });
    }, 3000);
}



    // Handle subscription form submission
    $('#subscriptionForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            subscription_start_date: $('input[name="subscription-start-date"]').val(),
            subscription_end_date: $('input[name="subscription-end-date"]').val(),
            parent_id: $('input[name="admin_user_id"]').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("admin.sub-users.addSubscription") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    // Reload page to show updated subscription
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr) {
                showNotification('error', 'Failed to update subscription.');
            }
        });
    });

    // Handle scratch form submission
    $('#scratchForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            scratch_count: $('input[name="scratch_count"]').val(),
            parent_id: $('input[name="admin_user_id"]').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("admin.sub-users.addScratch") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    // Reset form
                    $('#scratchForm')[0].reset();
                    // Reload DataTable
                    $('#scratch-history-table').DataTable().ajax.reload();
                    // Reload page to update scratch count
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr) {
                showNotification('error', 'Failed to add scratch count.');
            }
        });
    });


</script>


@endsection
