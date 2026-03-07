@extends('layouts.user')

<link rel="stylesheet" href="{{asset('assets/css/datatable.css')}}">

<style>
   .text-red-800 { color: red; }
   .bg-light-cyan { background-color: #dcfafcd9; }
</style>

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
            <p class="mt-2 text-sm text-muted-foreground">Manage your campaigns</p>
        </div>
        <button id="openAddCampaignModal" class="flex items-center px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Campaign
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg p-4" style="border: 1px solid #e4e4e4;">
        <div class="flex flex-wrap items-end gap-3">
            <!-- Status -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select id="filter_status" style="width:170px;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <!-- End Date From -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">End Date From</label>
                <input type="date" id="filter_date_from" style="width:170px;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
            </div>
            <!-- End Date To -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">End Date To</label>
                <input type="date" id="filter_date_to" style="width:170px;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
            </div>
            <!-- Buttons -->
            <div class="flex gap-2">
                <button id="applyFilters" style="padding:6px 18px;background:#0f172a;color:#fff;border:none;border-radius:6px;font-size:13px;cursor:pointer;">Apply</button>
                <button id="resetFilters" style="padding:6px 18px;background:#f3f4f6;color:#374151;border:1px solid #d1d5db;border-radius:6px;font-size:13px;cursor:pointer;">Reset</button>
            </div>
        </div>
    </div>

    <!-- DataTable Card -->
    <div class="bg-white shadow-sm rounded-lg" style="border: 1px solid #e4e4e4;">
        <div class="p-6">
            <table id="campaigns-table" class="data-table w-full" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Campaign Name</th>
                        <th>Campaign Image</th>
                        <th>Type</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Gift</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Campaign Modal -->
<div id="addCampaignModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Add New Campaign</h3>
            <button id="closeAddCampaignModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="addCampaignForm" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <!-- Campaign Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Name <span class="text-red-500">*</span></label>
                <input type="text" name="campaign_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Campaign Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Image</label>
                <input type="file" name="campaign_image" id="addCampaignImage" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border file:border-gray-300 file:text-xs file:font-medium file:bg-white hover:file:bg-gray-50 cursor-pointer">
                <div class="mt-2 flex items-center gap-2">
                    <div id="addNoImageText" class="h-14 w-14 bg-gray-100 border border-gray-200 rounded flex items-center justify-center text-xs text-gray-400">No Image</div>
                    <img id="addImagePreview" src="" alt="Preview" class="h-14 w-14 object-cover rounded border border-gray-200 hidden">
                </div>
            </div>
            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select --</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" id="cancelAddCampaign"
                        class="inline-flex items-center justify-center h-10 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="inline-flex items-center justify-center h-10 px-4 text-sm font-medium bg-slate-900 text-white rounded-md hover:bg-slate-800 transition-colors">
                    Create Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Campaign Modal -->
<div id="editCampaignModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Edit Campaign</h3>
            <button id="closeEditCampaignModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editCampaignForm" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="editCampaignId">
            <!-- Campaign Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Name <span class="text-red-500">*</span></label>
                <input type="text" name="campaign_name" id="editCampaignName" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Campaign Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Image <span class="text-gray-400 text-xs">(leave blank to keep current)</span></label>
                <input type="file" name="campaign_image" id="editCampaignImage" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border file:border-gray-300 file:text-xs file:font-medium file:bg-white hover:file:bg-gray-50 cursor-pointer">
                <div class="mt-2">
                    <img id="editImagePreview" src="" alt="Current Image" class="h-14 w-14 object-cover rounded border border-gray-200 hidden">
                    <div id="editNoImageText" class="h-14 w-14 bg-gray-100 border border-gray-200 rounded flex items-center justify-center text-xs text-gray-400">No Image</div>
                </div>
            </div>
            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" id="editEndDate" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" id="editCampaignStatus" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" id="cancelEditCampaign"
                        class="inline-flex items-center justify-center h-10 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="inline-flex items-center justify-center h-10 px-4 text-sm font-medium bg-slate-900 text-white rounded-md hover:bg-slate-800 transition-colors">
                    Update Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Delete Campaign</h3>
                    <p class="mt-1 text-sm text-gray-500">Are you sure you want to delete this campaign? This action cannot be undone.</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end rounded-b-lg">
            <button id="cancelDelete"
                    class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                Cancel
            </button>
            <button id="confirmDelete"
                    class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
<!-- DataTables JS -->
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

<script>
$(document).ready(function () {

    var table = $('#campaigns-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        paging: true,
        pageLength: 50,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: {
            search: '',
            searchPlaceholder: 'Search campaigns...',
        },
        ajax: {
            url: "{{ route('user.campaigns.data') }}",
            type: 'GET',
            data: function (d) {
                d.filter_status    = $('#filter_status').val();
                d.filter_date_from = $('#filter_date_from').val();
                d.filter_date_to   = $('#filter_date_to').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',     name: 'DT_RowIndex',     orderable: false, searchable: false },
            { data: 'campaign_name',   name: 'campaign_name' },
            { data: 'campaign_image',  name: 'campaign_image',  orderable: false, searchable: false },
            { data: 'type',            name: 'type',            orderable: false, searchable: false },
            { data: 'end_date',        name: 'end_date',        orderable: false },
            { data: 'status',          name: 'status',          searchable: false },
            { data: 'add_gift',        name: 'add_gift',        orderable: false, searchable: false },
            { data: 'action',          name: 'action',          orderable: false, searchable: false },
        ],
    });

    // ── Add Campaign Modal ──────────────────────────────────────
    $('#openAddCampaignModal').on('click', function () {
        $('#addCampaignForm')[0].reset();
        $('#addImagePreview').addClass('hidden');
        $('#addNoImageText').show();
        $('#addCampaignModal').removeClass('hidden');
    });
    $('#closeAddCampaignModal, #cancelAddCampaign').on('click', function () {
        $('#addCampaignModal').addClass('hidden');
    });

    // Add image preview
    $('#addCampaignImage').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#addNoImageText').hide();
                $('#addImagePreview').attr('src', e.target.result).removeClass('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            $('#addImagePreview').addClass('hidden');
            $('#addNoImageText').show();
        }
    });

    // Add Campaign Submit
    $('#addCampaignForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: "{{ route('user.campaigns.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('#addCampaignModal').addClass('hidden');
                    $('#addCampaignForm')[0].reset();
                    table.ajax.reload();
                    showNotification('success', response.message);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function (xhr) {
                showNotification('error', xhr.responseJSON?.message || 'Failed to create campaign.');
            }
        });
    });

    // ── Edit Campaign Modal ─────────────────────────────────────
    $('#closeEditCampaignModal, #cancelEditCampaign').on('click', function () {
        $('#editCampaignModal').addClass('hidden');
        $('#editCampaignForm')[0].reset();
    });

    // Edit image preview
    $('#editCampaignImage').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#editNoImageText').hide();
                $('#editImagePreview').attr('src', e.target.result).removeClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Edit Campaign Submit
    $('#editCampaignForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#editCampaignId').val();
        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        $.ajax({
            url: '/user/campaigns/' + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('#editCampaignModal').addClass('hidden');
                    $('#editCampaignForm')[0].reset();
                    table.ajax.reload();
                    showNotification('success', response.message);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function (xhr) {
                showNotification('error', xhr.responseJSON?.message || 'Failed to update campaign.');
            }
        });
    });

    // ── Delete Campaign Modal ───────────────────────────────────
    let deleteCampaignId = null;

    $('#cancelDelete').on('click', function () {
        $('#deleteConfirmModal').addClass('hidden');
        deleteCampaignId = null;
    });

    $('#confirmDelete').on('click', function () {
        if (!deleteCampaignId) return;
        $.ajax({
            url: '/user/campaigns/' + deleteCampaignId,
            type: 'POST',
            data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
            success: function (response) {
                $('#deleteConfirmModal').addClass('hidden');
                deleteCampaignId = null;
                if (response.success) {
                    table.ajax.reload();
                    showNotification('success', response.message);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function (xhr) {
                $('#deleteConfirmModal').addClass('hidden');
                showNotification('error', xhr.responseJSON?.message || 'Failed to delete campaign.');
            }
        });
    });

    // expose for DataTable action buttons
    window.deleteCampaign = function (id) {
        deleteCampaignId = id;
        $('#deleteConfirmModal').removeClass('hidden');
    };

    // ── Filters ─────────────────────────────────────────────────
    $('#applyFilters').on('click', function () {
        table.ajax.reload();
    });

    $('#resetFilters').on('click', function () {
        $('#filter_status').val('');
        $('#filter_date_from').val('');
        $('#filter_date_to').val('');
        table.ajax.reload();
    });

});

// Edit Campaign — called from DataTable action button
function editCampaign(id) {
    $.get('/user/campaigns/' + id + '/edit', function (response) {
        if (response.success) {
            const c = response.campaign;
            $('#editCampaignId').val(c.id);
            $('#editCampaignName').val(c.campaign_name);
            $('#editEndDate').val(c.end_date ? c.end_date.substring(0, 10) : '');
            $('#editCampaignStatus').val(c.status);

            // Show existing image if any
            if (c.campaign_image) {
                $('#editNoImageText').hide();
                $('#editImagePreview').attr('src', '/uploads/' + c.campaign_image).removeClass('hidden');
            } else {
                $('#editImagePreview').addClass('hidden');
                $('#editNoImageText').show();
            }

            $('#editCampaignModal').removeClass('hidden');
        } else {
            showNotification('error', response.message);
        }
    });
}

function showNotification(type, message) {
    const bg = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const el = $(`<div class="fixed top-4 right-4 ${bg} text-white px-6 py-3 rounded-lg shadow-lg z-[9999] text-sm">${message}</div>`);
    $('body').append(el);
    setTimeout(() => el.fadeOut(300, () => el.remove()), 3000);
}
</script>

@endsection
