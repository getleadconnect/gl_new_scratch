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
</style>
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div id="users-page-header" class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
            <p class="mt-2 text-sm text-muted-foreground">Manage all registered users</p>
        </div>
        <button id="openAddUserModal" class="flex items-center px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New User
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg p-4" style="border:1px solid #e4e4e4;">
        <div id="users-filter-fields" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">

            <!-- Role -->
            <div style="width:170px;">
                <label class="block text-xs font-medium text-gray-600 mb-1">Role</label>
                <select id="filter-role" style="width:170px;" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">All Roles</option>
                    <option value="1">Admin</option>
                    <option value="2">User</option>
                    <option value="3">Child</option>
                </select>
            </div>

            <!-- Status -->
            <div style="width:170px;">
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select id="filter-status" style="width:170px;" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="expired">Expired</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Date From -->
            <div style="width:170px;">
                <label class="block text-xs font-medium text-gray-600 mb-1">Created From</label>
                <input type="date" id="filter-date-from" style="width:170px;" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
            </div>

            <!-- Date To -->
            <div style="width:170px;">
                <label class="block text-xs font-medium text-gray-600 mb-1">Created To</label>
                <input type="date" id="filter-date-to" style="width:170px;" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button id="btn-apply-filter"
                    class="h-9 px-4 text-sm font-medium rounded-md text-white"
                    style="background:#18181b;border:none;cursor:pointer;white-space:nowrap;">
                    Apply
                </button>
                <button id="btn-reset-filter"
                    class="h-9 px-4 text-sm font-medium rounded-md"
                    style="background:#f3f4f6;border:1px solid #e4e4e4;cursor:pointer;white-space:nowrap;">
                    Reset
                </button>
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
                            <th>SlNo</th>
                            <th>Unique_ID</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Company</th>
                            <th>Role</th>
                            <th>Parent</th>
                            <th>Subscription</th>
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
                    <select name="role" id="role" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Role</option>
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                        <option value="3">Child</option>
                    </select>
                </div>

                <!-- Parent users -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent User <span class="text-red-500">*</span></label>
                    <select name="parent_user_id" id="parentUserId" class=" disabled w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled=true>
                        <option value="">Select Parent</option>
                        @if($parent_users)
                            @foreach($parent_users as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <!-- Company Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                                
                <!-- Subscription Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subscription Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="subscription_start_date" id="subscriptionStartDate" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Subscription End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subscription End Date <span class="text-red-500">*</span></label>
                    <input type="date" name="subscription_end_date" id="subscriptionEndDate" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                    <select name="role" id="editRole" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Role</option>
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                        <option value="3">Child</option>
                    </select>
                </div>

                <!-- Parent users -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent User <span class="text-red-500">*</span></label>
                    <select name="edit_parent_user_id" id="editParentUserId" class=" w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" >
                        <option value="">Select Parent</option>
                        @if($parent_users)
                            @foreach($parent_users as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
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
            <button id="confirmDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 h-10 px-4 py-2"
                style="background:#dc2626;color:#fff;border:none;cursor:pointer;"
                onmouseover="this.style.background='#b91c1c';this.style.transform='scale(1.03)';this.style.boxShadow='0 4px 12px rgba(220,38,38,0.4)'"
                onmouseout="this.style.background='#dc2626';this.style.transform='scale(1)';this.style.boxShadow='none'">
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

$("#role").change(function()
{
    if($(this).val()==3){
        $("#parentUserId").prop('disabled',false);
        $("#parentUserId").prop('required',true);
        $("#parentUserId").removeClass('disabled');
    }
    else{
        $("#parentUserId").prop('disabled',true);
        $("#parentUserId").prop('required',false);
        $("#parentUserId").val('');
        $("#parentUserId").addClass('disabled');

    }
});

$("#editRole").change(function()
{
     if($(this).val()==3){
        $("#editParentUserId").prop('disabled',false);
        $("#editParentUserId").prop('required',true);
        $("#editParentUserId").removeClass('disabled');
    }
    else{
        $("#editParentUserId").prop('disabled',true);
        $("#editParentUserId").prop('required',false);
        $("#editParentUserId").val('');
        $("#editParentUserId").addClass('disabled');
    }
});


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
            url: "{{ route('admin.users.data') }}",
            type: 'GET',
            data: function(d) {
                d.filter_role      = $('#filter-role').val();
                d.filter_status    = $('#filter-status').val();
                d.filter_date_from = $('#filter-date-from').val();
                d.filter_date_to   = $('#filter-date-to').val();
            }
        },

        columnDefs: [
        { width: "160px", targets: 3 },  // first column
        { width: "200px", targets: 4 },
        { width: "140px", targets: 9 }   // second column
        ],

        columns: [
            { data: 'DT_RowIndex',   name: 'DT_RowIndex',   orderable: false, searchable: false },
            { data: 'unique_id',     name: 'unique_id',     orderable: false },
            { data: 'name',          name: 'name',          orderable: true },
            { data: 'mobile',        name: 'mobile',        orderable: false, searchable: false },
            { data: 'company_name',  name: 'company_name',  orderable: false },
            { data: 'role',          name: 'role',          orderable: false, searchable: false },
            { data: 'parent_id',     name: 'parent_id',     orderable: false, searchable: false },
            { data: 'subscription',  name: 'subscription',  orderable: false, searchable: false },
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
        password: $('input[name="password"]').val(),
        parent_id:$('select[name="parent_user_id"]').val(),
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
                
                $("#subscriptionStartDate").prop('disabled',false);
                $("#subscriptionEndDate").prop('disabled',false);

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


// Handle Add User Form Submission
$('#parentUserId').on('change', function(e) {
    e.preventDefault();

 let userId=$(this).val();

    $.ajax({
        url: '{{ route("admin.users.admin-subscription-period",":id")}}'.replace(':id', userId),
        type: 'GET',
           success: function(response) {
            console.log(response);
           $("#subscriptionStartDate").val(response.data.start_date);
           $("#subscriptionEndDate").val(response.data.end_date);
           $("#subscriptionStartDate").prop('disabled',true);
           $("#subscriptionEndDate").prop('disabled',true);
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred while geting subscription date.';
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


$("#subscriptionStartDate").change(function()
{
    let date = new Date($(this).val()); // current date
    date.setFullYear(date.getFullYear() + 1);
    console.log(date);

    let year = date.getFullYear();
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let day = String(date.getDate()-1).padStart(2, '0');

    let newDate=`${year}-${month}-${day}`;

    $("#subscriptionEndDate").val(newDate);

})


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
                $("#editRole").change();
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
</script>
@endsection
