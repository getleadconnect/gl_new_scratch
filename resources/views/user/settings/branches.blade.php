@extends('layouts.user')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
<div class="space-y-4">

    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-1 text-sm text-muted-foreground">Manage your shop branches</p>
    </div>

    <!-- Split Panel -->
    <div class="flex gap-5 items-start" id="br-split-panel">

        <!-- ── Left Panel : Add / Edit Form ───────────────────────── -->
        <div class="bg-white rounded-lg shadow-sm flex-shrink-0" style="width:450px;border:1px solid #e4e4e4;">
            <div class="px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid #e4e4e4;">
                <h3 id="form-panel-title" class="text-sm font-semibold text-gray-800">Add Branch :</h3>
                <button id="btn-import-excel"
                    class="inline-flex items-center justify-center gap-1.5 h-8 px-3 text-xs font-medium rounded-md"
                    style="background:#18181b;color:#fff;border:none;cursor:pointer;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Import Excel
                </button>
            </div>
            <div class="p-5 space-y-4">
                <input type="hidden" id="edit_branch_id">

                <!-- Branch Name -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Branch Name <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="text" id="branch_name" maxlength="255"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                        placeholder="Branch name">
                    <p id="branch_name_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
                </div>

                <!-- Status -->
                <div id="br-status-wrap" style="width:200px;">
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Status <span style="color:#dc2626;">*</span>
                    </label>
                    <select id="branch_status"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                        <option value="">--select--</option>
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <p id="branch_status_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2 pt-1">
                    <button id="btn-submit"
                        class="flex-1 inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                        style="background:#18181b;color:#fff;border:none;cursor:pointer;">
                        Add
                    </button>
                    <button id="btn-cancel-edit"
                        class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                        style="display:none; background:#9ca3af;color:#fff;border:none;cursor:pointer;">
                        Cancel
                    </button>
                </div>

            </div>
        </div>

        <!-- ── Right Panel : DataTable ──────────────────────────────── -->
        <div class="bg-white rounded-lg shadow-sm flex-1" style="border:1px solid #e4e4e4;min-width:0;">
            <div class="px-5 py-3" style="border-bottom:1px solid #e4e4e4;">
                <h3 class="text-sm font-semibold text-gray-800">Branches List :</h3>
            </div>
            <div class="p-4" id="br-datatable-wrap">
                <table id="branches-table" class="data-table w-full" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Branch Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Import Excel Modal -->
<div id="importBranchModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg w-full mx-4" style="max-width:460px;">
        <div class="px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid #e4e4e4;">
            <h3 class="text-sm font-semibold text-gray-800">Import Branches from Excel</h3>
            <button id="closeImportModal" style="background:none;border:none;cursor:pointer;color:#6b7280;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="p-5 space-y-4">
            <!-- Format info -->
            <div class="rounded-md p-3 text-xs text-gray-600" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                <p class="font-semibold text-gray-700 mb-1">Required Excel Column Format:</p>
                <ul class="list-disc pl-4 space-y-0.5">
                    <li><span class="font-medium">branch_name</span> — Branch name (required)</li>
                    <li><span class="font-medium">status</span> — Active / Inactive / 1 / 0 (optional, defaults to Active)</li>
                </ul>
            </div>

            <!-- File input -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Select File <span style="color:#dc2626;">*</span>
                    <span class="text-gray-400 font-normal">(xlsx, xls, csv — max 5 MB)</span>
                </label>
                <input type="file" id="import_file" accept=".xlsx,.xls,.csv"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                <p id="import_file_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
            </div>
        </div>
        <div class="px-5 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;border-top:1px solid #e4e4e4;">
            <button id="cancelImportModal"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-gray-300 bg-white rounded-md">
                Cancel
            </button>
            <button id="confirmImportBranches"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium text-white rounded-md"
                style="background:#16a34a;">
                Upload
            </button>
        </div>
    </div>
</div>

<!-- Status Toggle Confirmation Modal -->
<div id="branchStatusModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center" id="branchStatusIconBg">
                    <svg class="w-6 h-6" id="branchStatusIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Change Status</h3>
                    <p class="mt-1 text-sm text-gray-500" id="branchStatusText">Are you sure you want to change the status?</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelBranchStatus"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                Cancel
            </button>
            <button id="confirmBranchStatus"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium text-white rounded-md"
                style="background:#18181b;">
                Confirm
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteBranchModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Delete Branch</h3>
                    <p class="mt-1 text-sm text-gray-500">Are you sure you want to delete this branch? This action cannot be undone.</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelDeleteBranch"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                Cancel
            </button>
            <button id="confirmDeleteBranch"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- Mobile responsive overrides -->
<style>
@media (max-width: 640px) {
    /* Stack panels vertically */
    #br-split-panel {
        flex-direction: column;
    }

    /* Left panel — override fixed 450px width */
    #br-split-panel > div:first-child {
        width: 100% !important;
        flex-shrink: unset;
    }

    /* Status select — full width */
    #br-status-wrap {
        width: 100% !important;
    }

    /* Submit button row — full width */
    #br-split-panel > div:first-child .flex.gap-2.pt-1 button {
        width: 100%;
    }

    /* DataTable — horizontal scroll */
    #br-datatable-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}
</style>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {

    var isEditMode = false;

    var table = $('#branches-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: false,
        paging: true,
        pageLength: 50,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: {
            search: '',
            searchPlaceholder: 'Search branches...',
        },
        ajax: {
            url: "{{ route('user.settings.branches.data') }}",
            type: 'GET',
        },
        columns: [
            { data: 'DT_RowIndex',  name: 'DT_RowIndex',  orderable: false, searchable: false },
            { data: 'branch_name',  name: 'branch_name',  searchable: true },
            { data: 'status_col',   name: 'status_col',   orderable: false, searchable: false },
            { data: 'action',       name: 'action',       orderable: false, searchable: false },
        ],
    });

    // ─── Form helpers ────────────────────────────────────────────
    function resetForm() {
        isEditMode = false;
        $('#edit_branch_id').val('');
        $('#branch_name').val('');
        $('#branch_status').val(1);
        $('#branch_name_error').hide().text('');
        $('#branch_status_error').hide().text('');
        $('#form-panel-title').text('Add Branch :');
        $('#btn-submit').text('Add');
        $('#btn-cancel-edit').hide();
    }

    function validateForm() {
        var valid = true;
        $('#branch_name_error').hide().text('');
        $('#branch_status_error').hide().text('');

        if (!$('#branch_name').val().trim()) {
            $('#branch_name_error').text('Branch name is required.').show();
            valid = false;
        }
        if ($('#branch_status').val() === '') {
            $('#branch_status_error').text('Please select a status.').show();
            valid = false;
        }
        return valid;
    }

    // ─── Add / Update submit ──────────────────────────────────────
    $('#btn-submit').on('click', function () {
        if (!validateForm()) return;

        var url    = isEditMode
            ? "{{ url('user/settings/branches') }}/" + $('#edit_branch_id').val()
            : "{{ route('user.settings.branches.store') }}";
        var method = isEditMode ? 'PUT' : 'POST';
        var btnText = isEditMode ? 'Update' : 'Add';

        $('#btn-submit').prop('disabled', true).text(isEditMode ? 'Updating...' : 'Adding...');

        $.ajax({
            url: url,
            type: isEditMode ? 'POST' : 'POST',
            data: {
                _method:      isEditMode ? 'PUT' : undefined,
                _token:       '{{ csrf_token() }}',
                branch_name:  $('#branch_name').val().trim(),
                status:       $('#branch_status').val(),
            },
            success: function (res) {
                $('#btn-submit').prop('disabled', false).text(btnText);
                if (res.success) {
                    resetForm();
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                $('#btn-submit').prop('disabled', false).text(btnText);
                var msg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message : 'Failed to save branch.';
                showNotification('error', msg);
            }
        });
    });

    // ─── Edit: fill form from row data ───────────────────────────
    window.editBranch = function (id, name, status) {
    
        isEditMode = true;
        $('#edit_branch_id').val(id);
        $('#branch_name').val(name);
        $('#branch_status').val(status);
        $('#branch_name_error').hide().text('');
        $('#branch_status_error').hide().text('');
        $('#form-panel-title').text('Edit Branch :');
        $('#btn-submit').text('Update');
        $('#btn-cancel-edit').show();
        // Scroll to top of form
        $('html, body').animate({ scrollTop: 0 }, 200);
    };

    $('#btn-cancel-edit').on('click', function () {
        resetForm();
    });

    // ─── Status Toggle Modal ──────────────────────────────────────
    var toggleBranchId = null;

    window.toggleBranchStatus = function (id, currentStatus) {
        toggleBranchId = id;

        if (currentStatus == 1) {
            $('#branchStatusText').text('This branch is currently Active. Do you want to set it to Inactive?');
            $('#branchStatusIconBg').css('background', '#fef9c3');
            $('#branchStatusIcon').css('stroke', '#ca8a04');
        } else {
            $('#branchStatusText').text('This branch is currently Inactive. Do you want to set it to Active?');
            $('#branchStatusIconBg').css('background', '#dcfce7');
            $('#branchStatusIcon').css('stroke', '#16a34a');
        }
        $('#branchStatusModal').removeClass('hidden');
    };

    $('#cancelBranchStatus').on('click', function () {
        $('#branchStatusModal').addClass('hidden');
        toggleBranchId = null;
    });

    $('#confirmBranchStatus').on('click', function () {
        if (!toggleBranchId) return;
        $.ajax({
            url: "{{ url('user/settings/branches') }}/" + toggleBranchId + "/toggle",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                $('#branchStatusModal').addClass('hidden');
                toggleBranchId = null;
                if (res.success) {
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function () {
                $('#branchStatusModal').addClass('hidden');
                showNotification('error', 'Failed to change status.');
            }
        });
    });

    // ─── Delete Modal ─────────────────────────────────────────────
    var deleteBranchId = null;

    window.deleteBranch = function (id) {
        deleteBranchId = id;
        $('#deleteBranchModal').removeClass('hidden');
    };

    $('#cancelDeleteBranch').on('click', function () {
        $('#deleteBranchModal').addClass('hidden');
        deleteBranchId = null;
    });

    $('#confirmDeleteBranch').on('click', function () {
        if (!deleteBranchId) return;
        $.ajax({
            url: "{{ url('user/settings/branches') }}/" + deleteBranchId,
            type: 'POST',
            data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
            success: function (res) {
                $('#deleteBranchModal').addClass('hidden');
                deleteBranchId = null;
                if (res.success) {
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function () {
                $('#deleteBranchModal').addClass('hidden');
                showNotification('error', 'Failed to delete branch.');
            }
        });
    });

    // ─── Import Excel Modal ───────────────────────────────────────
    $('#btn-import-excel').on('click', function () {
        $('#import_file').val('');
        $('#import_file_error').hide().text('');
        $('#importBranchModal').removeClass('hidden');
    });

    function closeImportModal() {
        $('#importBranchModal').addClass('hidden');
        $('#import_file').val('');
        $('#import_file_error').hide().text('');
    }

    $('#closeImportModal, #cancelImportModal').on('click', function () {
        closeImportModal();
    });

    $('#confirmImportBranches').on('click', function () {
        var file = $('#import_file')[0].files[0];
        $('#import_file_error').hide().text('');

        if (!file) {
            $('#import_file_error').text('Please select a file to import.').show();
            return;
        }

        var formData = new FormData();
        formData.append('import_file', file);
        formData.append('_token', '{{ csrf_token() }}');

        $('#confirmImportBranches').prop('disabled', true).text('Uploading...');

        $.ajax({
            url: "{{ route('user.settings.branches.import') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                $('#confirmImportBranches').prop('disabled', false).text('Upload');
                if (res.success) {
                    closeImportModal();
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                $('#confirmImportBranches').prop('disabled', false).text('Upload');
                var msg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message : 'Import failed.';
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
