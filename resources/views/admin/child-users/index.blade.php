@extends('layouts.admin')

<!-- Country Code Picker CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/intlTelInput.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

<style>
    .disabled { background-color: #ececec; }

    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes slideIn {
        from { transform: scale(0.95) translateY(-10px); opacity: 0; }
        to   { transform: scale(1) translateY(0); opacity: 1; }
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

    .iti { width: 100%; }
    .iti__flag-container { padding: 0; }
    .iti__selected-flag { padding: 0 8px; border-right: 1px solid #e4e4e4; }
    #mobileNumber, #editMobileNumber { padding-left: 52px; }
    .iti__country-list { border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0,0,0,.1); max-height: 200px; }
    .iti__country:hover { background-color: #f3f4f6; }
    .iti__country.iti__highlight { background-color: #3b82f6; }
</style>

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-2 text-sm text-muted-foreground">Manage your child users</p>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg p-4" style="border:1px solid #e4e4e4;">
        <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">

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
                <button id="btn-apply-filter" class="h-9 px-4 text-sm font-medium rounded-md text-white"
                    style="background:#18181b;border:none;cursor:pointer;white-space:nowrap;">
                    Apply
                </button>
                <button id="btn-reset-filter" class="h-9 px-4 text-sm font-medium rounded-md"
                    style="background:#f3f4f6;border:1px solid #e4e4e4;cursor:pointer;white-space:nowrap;">
                    Reset
                </button>
            </div>

        </div>
    </div>

    <!-- DataTable Card -->
    <div class="bg-white shadow-sm rounded-lg" style="border: 1px solid #e4e4e4;">
        <div class="p-6">
            <table id="child-users-table" class="data-table w-full" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Unique ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Company</th>
                        <th>Address</th>
                        <th>Subscription</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Add User Modal -->
<div id="addUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" style="background-color:#c9c4c442;">
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
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="tel" id="mobileNumber" name="mobile_full" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="hidden" name="country_code" id="countryCode">
                    <input type="hidden" name="mobile" id="mobileOnly">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                    <input type="text" name="company_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subscription Start</label>
                    <input type="date" name="subscription_start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subscription End</label>
                    <input type="date" name="subscription_end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" id="cancelAddUser" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-10 px-4 py-2">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-slate-900 text-white hover:bg-slate-800 h-10 px-4 py-2">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" style="background-color:#c9c4c442;">
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
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="editEmail" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="tel" id="editMobileNumber" name="mobile_full" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                    <input type="text" name="company_name" id="editCompanyName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" id="editAddress" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-500">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" id="editPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" id="cancelEditUser" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-10 px-4 py-2">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-slate-900 text-white hover:bg-slate-800 h-10 px-4 py-2">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
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
            <button id="cancelDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-10 px-4 py-2">
                Cancel
            </button>
            <button id="confirmDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 h-10 px-4 py-2">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/intlTelInput.min.js') }}"></script>

<script>
let iti;
let editIti;

$(document).ready(function () {

    // Initialize intl-tel-input for Add modal
    const phoneInput = document.querySelector('#mobileNumber');
    iti = window.intlTelInput(phoneInput, {
        initialCountry: 'in',
        preferredCountries: ['us', 'gb', 'in', 'ae'],
        separateDialCode: true,
        utilsScript: "{{ asset('assets/js/intlTelInput_utils.js') }}"
    });

    // Initialize intl-tel-input for Edit modal
    const editPhoneInput = document.querySelector('#editMobileNumber');
    editIti = window.intlTelInput(editPhoneInput, {
        initialCountry: 'in',
        preferredCountries: ['us', 'gb', 'in', 'ae'],
        separateDialCode: true,
        utilsScript: "{{ asset('assets/js/intlTelInput_utils.js') }}"
    });

    var table = $('#child-users-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: false,
        paging: true,
        pageLength: 50,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: {
            search: '',
            searchPlaceholder: 'Search users...',
        },
        ajax: {
            url: "{{ route('admin.child-users.data') }}",
            type: 'GET',
            data: function (d) {
                d.filter_status    = $('#filter-status').val();
                d.filter_date_from = $('#filter-date-from').val();
                d.filter_date_to   = $('#filter-date-to').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',  name: 'DT_RowIndex',  orderable: false, searchable: false },
            { data: 'unique_id',    name: 'unique_id',    orderable: false },
            { data: 'name',         name: 'name',         orderable: true },
            { data: 'email',        name: 'email',        orderable: true },
            { data: 'mobile',       name: 'mobile',       orderable: false, searchable: false },
            { data: 'company_name', name: 'company_name', orderable: false },
            { data: 'address',      name: 'address',      orderable: false, searchable: false },
            { data: 'subscription', name: 'subscription', orderable: false, searchable: false },
            { data: 'status',       name: 'status',       orderable: false, searchable: false },
            { data: 'created_date', name: 'created_at',   orderable: true,  searchable: false },
            { data: 'action',       name: 'action',       orderable: false, searchable: false },
        ],
    });

    // Filters
    $('#btn-apply-filter').on('click', function () {
        table.ajax.reload();
    });
    $('#btn-reset-filter').on('click', function () {
        $('#filter-status').val('');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        table.ajax.reload();
    });

    // ─── Add User Modal ───────────────────────────────────────────
    $('#openAddUserModal').on('click', function () {
        $('#addUserModal').removeClass('hidden');
    });

    $('#closeAddUserModal, #cancelAddUser').on('click', function () {
        $('#addUserModal').addClass('hidden');
        $('#addUserForm')[0].reset();
    });

    $('#addUserModal').on('click', function (e) {
        if (e.target === this) {
            $(this).addClass('hidden');
            $('#addUserForm')[0].reset();
        }
    });

    $('#addUserForm').on('submit', function (e) {
        e.preventDefault();

        const selectedCountryData = iti.getSelectedCountryData();
        const fullNumber          = iti.getNumber();
        const countryCode         = '+' + selectedCountryData.dialCode;
        const nationalNumber      = fullNumber.replace(countryCode, '').trim();

        const formData = {
            name:                    $('input[name="name"]').val(),
            email:                   $('input[name="email"]').val(),
            country_code:            countryCode,
            mobile:                  nationalNumber,
            company_name:            $('input[name="company_name"]').val(),
            address:                 $('textarea[name="address"]').val(),
            password:                $('input[name="password"]').val(),
            subscription_start_date: $('input[name="subscription_start_date"]').val(),
            subscription_end_date:   $('input[name="subscription_end_date"]').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: "{{ route('admin.child-users.store') }}",
            type: 'POST',
            data: formData,
            success: function (res) {
                if (res.success) {
                    $('#addUserModal').addClass('hidden');
                    $('#addUserForm')[0].reset();
                    showNotification('success', res.message);
                    table.ajax.reload();
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                let msg = 'An error occurred while creating the user.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showNotification('error', msg);
            }
        });
    });

    // ─── Edit User Modal ──────────────────────────────────────────
    $('#closeEditUserModal, #cancelEditUser').on('click', function () {
        $('#editUserModal').addClass('hidden');
        $('#editUserForm')[0].reset();
    });

    $('#editUserModal').on('click', function (e) {
        if (e.target === this) {
            $(this).addClass('hidden');
            $('#editUserForm')[0].reset();
        }
    });

    $('#editUserForm').on('submit', function (e) {
        e.preventDefault();
        const userId = $('#editUserId').val();

        const selectedCountryData = editIti.getSelectedCountryData();
        const fullNumber          = editIti.getNumber();
        const countryCode         = '+' + selectedCountryData.dialCode;
        const nationalNumber      = fullNumber.replace(countryCode, '').trim();

        const formData = {
            name:                    $('#editName').val(),
            email:                   $('#editEmail').val(),
            country_code:            countryCode,
            mobile:                  nationalNumber,
            company_name: $('#editCompanyName').val(),
            address:      $('#editAddress').val(),
            password:     $('#editPassword').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: "{{ url('admin/child-users') }}/" + userId,
            type: 'PUT',
            data: formData,
            success: function (res) {
                if (res.success) {
                    $('#editUserModal').addClass('hidden');
                    $('#editUserForm')[0].reset();
                    showNotification('success', res.message);
                    table.ajax.reload();
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                let msg = 'An error occurred while updating the user.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showNotification('error', msg);
            }
        });
    });

    // ─── Delete Modal ─────────────────────────────────────────────
    let deleteUserId = null;

    $('#cancelDelete').on('click', function () {
        $('#deleteConfirmModal').addClass('hidden');
        deleteUserId = null;
    });

    $('#deleteConfirmModal').on('click', function (e) {
        if (e.target === this) {
            $(this).addClass('hidden');
            deleteUserId = null;
        }
    });

    $('#confirmDelete').on('click', function () {
        if (!deleteUserId) return;
        $.ajax({
            url: "{{ url('admin/child-users') }}/" + deleteUserId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                $('#deleteConfirmModal').addClass('hidden');
                deleteUserId = null;
                if (res.success) {
                    showNotification('success', res.message);
                    table.ajax.reload();
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function () {
                $('#deleteConfirmModal').addClass('hidden');
                showNotification('error', 'An error occurred while deleting the user.');
                deleteUserId = null;
            }
        });
    });

    // expose deleteUser globally for DataTable action buttons
    window.deleteUser = function (id) {
        deleteUserId = id;
        $('#deleteConfirmModal').removeClass('hidden');
    };

});

// expose editUser globally for DataTable action buttons
function editUser(userId) {
    $.ajax({
        url: "{{ url('admin/child-users') }}/" + userId + '/edit',
        type: 'GET',
        success: function (res) {
            if (res.success) {
                const u = res.user;
                $('#editUserId').val(u.id);
                $('#editName').val(u.name);
                $('#editEmail').val(u.email);
                $('#editCompanyName').val(u.company_name);
                $('#editAddress').val(u.address);
                editIti.setNumber(u.country_code + u.mobile);
                $('#editUserModal').removeClass('hidden');
            } else {
                showNotification('error', res.message);
            }
        },
        error: function () {
            showNotification('error', 'Failed to fetch user data.');
        }
    });
}

function showNotification(type, message) {
    var bg = type === 'success' ? '#16a34a' : '#dc2626';
    var el = $('<div style="position:fixed;top:16px;right:16px;z-index:9999;padding:12px 20px;border-radius:8px;color:#fff;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,.15);background:' + bg + ';">' + message + '</div>');
    $('body').append(el);
    setTimeout(function () { el.fadeOut(300, function () { el.remove(); }); }, 3000);
}
</script>

@endsection
