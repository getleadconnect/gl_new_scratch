@extends('layouts.user')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">All gifts added to your campaigns</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white shadow-sm rounded-lg" style="border: 1px solid #e4e4e4;">
        <div class="px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #e4e4e4;">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4-2A1 1 0 018 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            <span class="text-sm font-semibold text-gray-700">Filter By</span>
        </div>
        <div class="p-4">
            <div class="flex flex-wrap items-end gap-3">

                <!-- Campaign -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Campaign</label>
                    <select id="filter-campaign"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary"
                        style="min-width:200px;">
                        <option value="">All Campaigns</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Status</label>
                    <select id="filter-status"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary"
                        style="min-width:150px;">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button id="btn-filter"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        style="background:#18181b;color:#fff;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4-2A1 1 0 018 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        Filter
                    </button>
                    <button id="btn-clear"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        style="background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;">
                        Clear
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- DataTable Card -->
    <div class="bg-white shadow-sm rounded-lg" style="border: 1px solid #e4e4e4;">
        <div class="px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #e4e4e4;">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
            </svg>
            <span class="text-sm font-semibold text-gray-700">Gifts</span>
        </div>
        <div class="p-4">
            <table id="gifts-list-table" class="data-table w-full" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Campaign</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Gift Count</th>
                        <th>Balance</th>
                        <th>Win</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Edit Gift Modal -->
<div id="editGiftModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg w-full mx-4" style="max-width:480px;">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #e5e7eb;">
            <h3 class="text-base font-semibold text-gray-900">Edit Gift</h3>
            <button id="closeEditGiftModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 space-y-4">
            <input type="hidden" id="edit_gift_id">

            <!-- Current Image Preview -->
            <div id="current_image_wrap" style="display:none;">
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
                <img id="current_gift_image" src="" alt="Gift Image"
                    style="height:64px;width:64px;object-fit:cover;border-radius:6px;border:1px solid #e5e7eb;">
            </div>

            <!-- Upload New Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Gift Image <span style="font-size:11px;color:#6b7280;">(optional – upload to replace)</span>
                </label>
                <input type="file" id="edit_gift_image" accept="image/*"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description <span style="color:#dc2626;">*</span>
                </label>
                <textarea id="edit_gift_description" rows="3"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                    placeholder="Gift description"></textarea>
                <p id="edit_desc_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="flex items-center gap-5">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="edit_gift_status" value="1"
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        Active
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="edit_gift_status" value="0"
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        Inactive
                    </label>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div style="border-top:1px solid #e5e7eb;"></div>

        <!-- Footer -->
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelEditGift"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                style="background:#979696;color:#fff;border:none;cursor:pointer;">
                Close
            </button>
            <button id="updateGift"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                style="background:#2563eb;color:#fff;border:none;cursor:pointer;">
                Update Gift
            </button>
        </div>
    </div>
</div>

<!-- Status Toggle Confirmation Modal -->
<div id="giftStatusModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center" id="giftStatusIconBg">
                    <svg class="w-6 h-6" id="giftStatusIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Change Status</h3>
                    <p class="mt-1 text-sm text-gray-500" id="giftStatusText">Are you sure you want to change the status?</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelGiftStatus"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                Cancel
            </button>
            <button id="confirmGiftStatus"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium text-white rounded-md"
                style="background:#18181b;">
                Confirm
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteGiftModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Delete Gift</h3>
                    <p class="mt-1 text-sm text-gray-500">Are you sure you want to delete this gift? This action cannot be undone.</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelDeleteGift"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                Cancel
            </button>
            <button id="confirmDeleteGift"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {

    var table = $('#gifts-list-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: false,
        paging: true,
        pageLength: 50,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: {
            search: '',
            searchPlaceholder: 'Search gifts...',
        },
        ajax: {
            url: "{{ route('user.gifts-list.data') }}",
            type: 'GET',
            data: function (d) {
                d.campaign_id = $('#filter-campaign').val();
                d.status      = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',    name: 'DT_RowIndex',   orderable: false, searchable: false },
            { data: 'campaign_name',  name: 'campaign_name', orderable: false, searchable: false },
            { data: 'image_col',      name: 'image_col',     orderable: false, searchable: false },
            { data: 'description',    name: 'description',   searchable: true },
            { data: 'gift_count',     name: 'gift_count',    searchable: false },
            { data: 'balance_count',  name: 'balance_count', searchable: false },
            { data: 'win_loss_col',   name: 'win_loss_col',  orderable: false, searchable: false },
            { data: 'status_col',     name: 'status_col',    orderable: false, searchable: false },
            { data: 'action',         name: 'action',        orderable: false, searchable: false },
        ],
    });

    // Filter
    $('#btn-filter').on('click', function () {
        table.ajax.reload();
    });

    // Clear
    $('#btn-clear').on('click', function () {
        $('#filter-campaign').val('');
        $('#filter-status').val('');
        table.ajax.reload();
    });

    // ─── Edit Gift Modal ──────────────────────────────────────────
    function closeEditGiftModal() {
        $('#editGiftModal').addClass('hidden');
    }

    $('#closeEditGiftModal, #cancelEditGift').on('click', function () {
        closeEditGiftModal();
    });

    window.editGiftItem = function (id) {
        $('#edit_desc_error').hide().text('');
        $('#edit_gift_image').val('');

        $.ajax({
            url: "{{ url('user/gifts-list') }}/" + id + "/edit",
            type: 'GET',
            success: function (res) {
                if (!res.success) {
                    showNotification('error', res.message || 'Failed to load gift.');
                    return;
                }
                var d = res.data;
                $('#edit_gift_id').val(d.id);
                $('#edit_gift_description').val(d.description);
                $('input[name="edit_gift_status"][value="' + d.status + '"]').prop('checked', true);

                if (d.image_url) {
                    $('#current_gift_image').attr('src', d.image_url);
                    $('#current_image_wrap').show();
                } else {
                    $('#current_image_wrap').hide();
                }

                $('#editGiftModal').removeClass('hidden');
            },
            error: function () {
                showNotification('error', 'Failed to load gift data.');
            }
        });
    };

    $('#updateGift').on('click', function () {
        var description = $('#edit_gift_description').val().trim();
        $('#edit_desc_error').hide().text('');

        if (!description) {
            $('#edit_desc_error').text('Description is required.').show();
            return;
        }

        var id     = $('#edit_gift_id').val();
        var status = $('input[name="edit_gift_status"]:checked').val();
        var formData = new FormData();

        formData.append('_method', 'PUT');
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('description', description);
        formData.append('status', status);

        var imageFile = $('#edit_gift_image')[0].files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        $('#updateGift').prop('disabled', true).text('Updating...');

        $.ajax({
            url: "{{ url('user/gifts-list') }}/" + id,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                $('#updateGift').prop('disabled', false).text('Update Gift');
                if (res.success) {
                    closeEditGiftModal();
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                $('#updateGift').prop('disabled', false).text('Update Gift');
                var msg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Failed to update gift.';
                showNotification('error', msg);
            }
        });
    });

    // ─── Status Toggle Modal ──────────────────────────────────────
    var toggleGiftId      = null;
    var toggleGiftCurrent = null;

    window.toggleGiftStatus = function (id, currentStatus) {
        toggleGiftId      = id;
        toggleGiftCurrent = currentStatus;

        if (currentStatus == 1) {
            $('#giftStatusText').text('This gift is currently Active. Do you want to set it to Inactive?');
            $('#giftStatusIconBg').css('background', '#fef9c3');
            $('#giftStatusIcon').css('stroke', '#ca8a04');
        } else {
            $('#giftStatusText').text('This gift is currently Inactive. Do you want to set it to Active?');
            $('#giftStatusIconBg').css('background', '#dcfce7');
            $('#giftStatusIcon').css('stroke', '#16a34a');
        }

        $('#giftStatusModal').removeClass('hidden');
    };

    $('#cancelGiftStatus').on('click', function () {
        $('#giftStatusModal').addClass('hidden');
        toggleGiftId = null;
    });

    $('#confirmGiftStatus').on('click', function () {
        if (!toggleGiftId) return;
        $.ajax({
            url: "{{ url('user/gifts-list') }}/" + toggleGiftId + "/toggle",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                $('#giftStatusModal').addClass('hidden');
                toggleGiftId = null;
                if (res.success) {
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function () {
                $('#giftStatusModal').addClass('hidden');
                showNotification('error', 'Failed to change status.');
            }
        });
    });

    // ─── Delete Modal ─────────────────────────────────────────────
    var deleteGiftId = null;

    window.deleteGiftItem = function (id) {
        deleteGiftId = id;
        $('#deleteGiftModal').removeClass('hidden');
    };

    $('#cancelDeleteGift').on('click', function () {
        $('#deleteGiftModal').addClass('hidden');
        deleteGiftId = null;
    });

    $('#confirmDeleteGift').on('click', function () {
        if (!deleteGiftId) return;
        $.ajax({
            url: "{{ url('user/gifts-list') }}/" + deleteGiftId,
            type: 'POST',
            data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
            success: function (res) {
                $('#deleteGiftModal').addClass('hidden');
                deleteGiftId = null;
                if (res.success) {
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                $('#deleteGiftModal').addClass('hidden');
                var msg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Failed to delete gift.';
                showNotification('error', msg);
            }
        });
    });

});

function showNotification(type, message) {
    var bg = type === 'success' ? '#16a34a' : (type === 'error' ? '#dc2626' : '#2563eb');
    var el = $('<div style="position:fixed;top:16px;right:16px;z-index:9999;padding:12px 20px;border-radius:8px;color:#fff;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,.15);background:' + bg + ';">' + message + '</div>');
    $('body').append(el);
    setTimeout(function () { el.fadeOut(300, function () { el.remove(); }); }, 3000);
}
</script>

@endsection
