@extends('layouts.user')

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideIn { from { transform: scale(0.95) translateY(-10px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
    #editLogoModal, #deleteConfirmModal { animation: fadeIn 0.2s ease-out; }
    #editLogoModal .animate-in, #deleteConfirmModal .animate-in { animation: slideIn 0.2s ease-out; }
</style>

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-2 text-sm text-muted-foreground">Manage your company logos and favicons. You can have one active logo and one active favicon simultaneously.</p>
    </div>

    <!-- Two Column Layout -->
    <div id="logo-split-panel" style="display:flex;gap:20px;">

        <!-- Left Column — Add Form -->
        <div id="logo-left-col" style="width:300px;flex-shrink:0;">
            <div class="bg-white shadow-sm rounded-lg" style="border:1px solid #e4e4e4;">
                <div class="px-5 py-4" style="border-bottom:1px solid #e4e4e4;">
                    <h3 class="text-sm font-bold text-gray-800">Add Logo / Favicon</h3>
                </div>
                <form id="addLogoForm" class="p-5 space-y-4" enctype="multipart/form-data">

                    <!-- Logo Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo Name</label>
                        <input type="text" name="name" id="add_name" placeholder="Enter logo name (optional)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="add_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="logo">Logo</option>
                            <option value="favicon">Favicon</option>
                        </select>
                    </div>

                    <!-- Logo Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo Image</label>
                        <input type="file" name="logo_image" id="add_image" accept=".jpg,.jpeg,.png,.gif,.svg"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Max size: 2MB. Formats: JPG, PNG, GIF, SVG</p>
                    </div>

                    <!-- Set as Active -->
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="add_active" value="1"
                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" checked>
                        <label for="add_active" class="text-sm text-gray-700">Set as Active </label>
                    </div>
                    <p class="text-xs text-gray-400" style="margin-top:4px;">Note: Only one logo of each type can be active at a time. Setting this as active will deactivate all other logos of the same type.</p>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full py-2 px-4 bg-slate-900 text-white rounded-md text-sm font-medium hover:bg-slate-800 transition-colors">
                        Add Logo
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column — DataTable -->
        <div id="logo-right-col" style="flex:1;min-width:0;">
            <div id="logo-datatable-wrap" class="bg-white shadow-sm rounded-lg" style="border:1px solid #e4e4e4;">
                <div class="p-6">
                    <table id="logos-table" class="data-table w-full" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Logo/Favicon</th>
                                <th>Name</th>
                                <th>Type</th>
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

    </div>

</div>

<!-- Edit Logo Modal -->
<div id="editLogoModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4 animate-in">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Edit Logo</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editLogoForm" class="p-6 space-y-4" enctype="multipart/form-data">
            <input type="hidden" id="edit_id">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Name</label>
                <input type="text" id="edit_name" placeholder="Enter logo name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select id="edit_type"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="logo">Logo</option>
                    <option value="favicon">Favicon</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Image <span class="text-gray-400">(Leave blank to keep current)</span></label>
                <input type="file" id="edit_image" accept=".jpg,.jpeg,.png,.gif,.svg"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div id="edit_preview" class="mt-2"></div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="edit_active" value="1"
                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                <label for="edit_active" class="text-sm text-gray-700">Set as Active</label>
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-slate-900 text-white rounded-md text-sm font-medium hover:bg-slate-800">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirm Modal -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 animate-in">
        <div class="p-6 flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Delete Logo</h3>
                <p class="mt-1 text-sm text-gray-500">Are you sure you want to delete this logo? This action cannot be undone.</p>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end rounded-b-lg">
            <button onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 rounded-md text-sm font-medium text-white"
                style="background:#dc2626;cursor:pointer;"
                onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">Delete</button>
        </div>
    </div>
</div>

<!-- Mobile responsive overrides -->
<style>
@media (max-width: 640px) {
    #logo-split-panel { flex-direction: column; }
    #logo-left-col { width: 100% !important; }
    #logo-right-col { width: 100% !important; }
}
</style>

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
var deleteId = null;

$(document).ready(function () {

    // ── DataTable ──
    var table = $('#logos-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        paging: true,
        pageLength: 10,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: { search: '', searchPlaceholder: 'Search logos...' },
        ajax: { url: '{{ route("user.settings.logo-favicon.data") }}', type: 'GET' },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image_col',   name: 'image_col',   orderable: false, searchable: false },
            { data: 'name',        name: 'name',        orderable: true },
            { data: 'type_col',    name: 'type',        orderable: true, searchable: false },
            { data: 'status_col',  name: 'is_active',   orderable: false, searchable: false },
            { data: 'created_at',  name: 'created_at',  orderable: false },
            { data: 'action',      name: 'action',      orderable: false, searchable: false },
        ],
    });

    // ── Add Logo ──
    $('#addLogoForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('name', $('#add_name').val());
        formData.append('type', $('#add_type').val());
        formData.append('_token', '{{ csrf_token() }}');
        if ($('#add_image')[0].files[0]) formData.append('logo_image', $('#add_image')[0].files[0]);
        if ($('#add_active').is(':checked')) formData.append('is_active', '1');

        $.ajax({
            url: '{{ route("user.settings.logo-favicon.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    showNotification('success', res.message);
                    $('#addLogoForm')[0].reset();
                    table.ajax.reload();
                } else { showNotification('error', res.message); }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON?.errors ? Object.values(xhr.responseJSON.errors).flat().join(', ') : (xhr.responseJSON?.message || 'Error occurred.');
                showNotification('error', msg);
            }
        });
    });

    // ── Edit Modal ──
    $('#closeEditModal, #cancelEditModal').on('click', function() { $('#editLogoModal').addClass('hidden'); });
    $('#editLogoModal').on('click', function(e) { if (e.target === this) $(this).addClass('hidden'); });

    $('#editLogoForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        var formData = new FormData();
        formData.append('name', $('#edit_name').val());
        formData.append('type', $('#edit_type').val());
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        if ($('#edit_image')[0].files[0]) formData.append('logo_image', $('#edit_image')[0].files[0]);
        if ($('#edit_active').is(':checked')) formData.append('is_active', '1');

        $.ajax({
            url: '{{ route("user.settings.logo-favicon.update", ":id") }}'.replace(':id', id),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    $('#editLogoModal').addClass('hidden');
                    showNotification('success', res.message);
                    table.ajax.reload();
                } else { showNotification('error', res.message); }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON?.message || 'Error occurred.';
                showNotification('error', msg);
            }
        });
    });

    // ── Delete Modal ──
    $('#deleteConfirmModal').on('click', function(e) { if (e.target === this) closeDeleteModal(); });

    $('#confirmDeleteBtn').on('click', function() {
        if (!deleteId) return;
        $.ajax({
            url: '{{ route("user.settings.logo-favicon.destroy", ":id") }}'.replace(':id', deleteId),
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                closeDeleteModal();
                if (res.success) { showNotification('success', res.message); table.ajax.reload(); }
                else { showNotification('error', res.message); }
            },
            error: function() { closeDeleteModal(); showNotification('error', 'Delete failed.'); }
        });
    });

});

// ── Global functions ──
function editLogo(id) {
    $.get('{{ route("user.settings.logo-favicon.edit", ":id") }}'.replace(':id', id), function(res) {
        if (res.success) {
            var logo = res.logo;
            $('#edit_id').val(logo.id);
            $('#edit_name').val(logo.name);
            $('#edit_type').val(logo.type);
            $('#edit_active').prop('checked', logo.is_active == 1);
            $('#edit_preview').html(logo.logo_image ? '<img src="{{ url("uploads") }}/' + logo.logo_image + '" style="max-height:50px;border:1px solid #e5e7eb;border-radius:4px;padding:2px;">' : '');
            $('#edit_image').val('');
            $('#editLogoModal').removeClass('hidden');
        } else { showNotification('error', res.message); }
    });
}

function deleteLogo(id) { deleteId = id; $('#deleteConfirmModal').removeClass('hidden'); }
function closeDeleteModal() { $('#deleteConfirmModal').addClass('hidden'); deleteId = null; }

function showNotification(type, message) {
    var bg = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    var el = $('<div class="fixed top-4 right-4 ' + bg + ' text-white px-6 py-3 rounded-lg shadow-lg z-50">' + message + '</div>');
    $('body').append(el);
    setTimeout(function() { el.fadeOut(300, function() { $(this).remove(); }); }, 3000);
}
</script>

@endsection
