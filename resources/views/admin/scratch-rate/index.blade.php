@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

<style>
    @keyframes fadeIn  { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideIn { from { transform: scale(0.95) translateY(-10px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
    #addPackageModal, #editPackageModal, #deleteConfirmModal { animation: fadeIn 0.2s ease-out; }
    #addPackageModal .animate-in, #editPackageModal .animate-in, #deleteConfirmModal .animate-in { animation: slideIn 0.2s ease-out; }
</style>

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div id="sp-page-header" class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
            <p class="mt-2 text-sm text-muted-foreground">Manage scratch count packages and rates</p>
        </div>
        <!--<button id="openAddModal"
            class="flex items-center px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Package
        </button>  -->
    </div>

    <!-- Two-column layout: 25% left / 75% right -->
    <div id="sp-split-panel" style="display:flex;gap:20px;">

        <!-- Left Column (25%) -->
        <div id="sp-left-col" style="width:25%;flex-shrink:0;">
            <div class="bg-white shadow-sm rounded-lg" style="border:1px solid #e4e4e4;">
                <div class="px-5 py-4" style="border-bottom:1px solid #e4e4e4;">
                    <h3 class="text-sm font-bold text-gray-800" style="font-size:16px;">Summary</h3>
                </div>
                <div class="p-5" style="display:flex;gap:16px;">
                    <div style="flex:1;text-align:center;">
                        <p class="text-xs text-muted-foreground">Total Packages</p>
                        <p class="text-2xl font-bold text-foreground" id="summaryTotal">—</p>
                    </div>
                    <div style="flex:1;text-align:center;border-left:1px solid #e5e7eb;border-right:1px solid #e5e7eb;padding:0 12px;">
                        <p class="text-xs text-muted-foreground">Lowest Rate</p>
                        <p class="text-lg font-semibold text-green-600" id="summaryLowest">—</p>
                    </div>
                    <div style="flex:1;text-align:center;">
                        <p class="text-xs text-muted-foreground">Highest Rate</p>
                        <p class="text-lg font-semibold text-red-600" id="summaryHighest">—</p>
                    </div>
                </div>
            </div>

            <!-- Future Use Card -->
            <div class="bg-white shadow-sm rounded-lg mt-4" style="border:1px solid #e4e4e4;">
                <div class="px-5 py-4" style="border-bottom:1px solid #e4e4e4;">
                    <h3 class="text-sm font-bold text-gray-800" style="font-size:16px;">Add New Package</h3>
                </div>
                <div class="p-1">
                    <form id="addPackageForm" class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Scratch Count <span class="text-red-500">*</span></label>
                            <input type="number" name="scratch_count" id="add_scratch_count" min="1" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rate per Scratch (₹) <span class="text-red-500">*</span></label>
                            <input type="number" name="rate" id="add_rate" step="0.01" min="0" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount (₹)</label>
                            <input type="text" id="add_total" readonly
                                class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-500">
                        </div>
                        <div class="flex gap-3 justify-end pt-2">
                            <button type="submit"
                                class="px-4 py-2 bg-slate-900 text-white rounded-md text-sm font-medium hover:bg-slate-800">Save</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>

         <!-- Right Column (75%) — DataTable -->
        <div id="sp-right-col" style="width:75%;min-width:0;">
            <div id="sp-datatable-wrap">
                <div class="bg-white shadow-sm rounded-lg" style="border:1px solid #e4e4e4;">
                    <div class="p-6">
                        <table id="sp-table" class="data-table w-full" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Scratch Count</th>
                                    <th>Rate (₹/scratch)</th>
                                    <th>Total Amount (₹)</th>
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

</div>


<!-- Edit Package Modal -->
<div id="editPackageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4 animate-in">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Edit Package</h3>
            <button id="closeEditModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editPackageForm" class="p-6 space-y-4">
            <input type="hidden" id="edit_id">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Scratch Count <span class="text-red-500">*</span></label>
                <input type="number" name="scratch_count" id="edit_scratch_count" min="1" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rate per Scratch (₹) <span class="text-red-500">*</span></label>
                <input type="number" name="rate" id="edit_rate" step="0.01" min="0" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount (₹)</label>
                <input type="text" id="edit_total" readonly
                    class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-500">
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" id="cancelEdit"
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
                <h3 class="text-lg font-semibold text-gray-900">Delete Package</h3>
                <p class="mt-1 text-sm text-gray-500">Are you sure you want to delete this package? This action cannot be undone.</p>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end rounded-b-lg">
            <button id="cancelDelete" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
            <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

<!-- Mobile responsive overrides -->
<style>
@media (max-width: 640px) {
    #sp-page-header { flex-direction: column; align-items: stretch; gap: 12px; }
    #sp-page-header > button { width: 100%; justify-content: center; }
    #sp-split-panel { flex-direction: column; }
    #sp-left-col { width: 100% !important; }
    #sp-right-col { width: 100% !important; }
    #sp-datatable-wrap .bg-white { overflow-x: auto; }
}
</style>

<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
var deleteId = null;

$(document).ready(function () {

    // ── DataTable ──────────────────────────────────────────────────────
    var table = $('#sp-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        paging: true,
        pageLength: 25,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: { search: '', searchPlaceholder: 'Search...' },
        ajax: { url: '{{ route("admin.scratch-rate.data") }}', type: 'GET' },
        
        columns: [
            { data: 'DT_RowIndex',       name: 'DT_RowIndex',   orderable: false, searchable: false },
            { data: 'scratch_count_fmt', name: 'scratch_count', orderable: true },
            { data: 'rate_fmt',          name: 'rate',          orderable: true },
            { data: 'total_amount_fmt',  name: 'total_amount',  orderable: false, searchable: false },
            { data: 'created_at',        name: 'created_at',    orderable: true,  searchable: false },
            { data: 'action',            name: 'action',        orderable: false, searchable: false },
        ],
    });

    // ── Load summary stats ────────────────────────────────────────────
    function loadSummary() {
        $.ajax({
            url: '{{ route("admin.scratch-rate.data") }}',
            type: 'GET',
            data: { length: -1 },
            success: function(res) {
                var data = res.data || [];
                $('#summaryTotal').text(data.length);
                if (data.length > 0) {
                    var rates = data.map(function(d) { return parseFloat(d.rate) || 0; });
                    $('#summaryLowest').text('\u20B9' + Math.min.apply(null, rates).toFixed(2));
                    $('#summaryHighest').text('\u20B9' + Math.max.apply(null, rates).toFixed(2));
                } else {
                    $('#summaryLowest').text('—');
                    $('#summaryHighest').text('—');
                }
            }
        });
    }
    loadSummary();
    table.on('draw', function() { loadSummary(); });

    // ── Auto-calc total ────────────────────────────────────────────────
    function calcTotal(countId, rateId, totalId) {
        $(countId + ', ' + rateId).on('input', function () {
            var c = parseFloat($(countId).val()) || 0;
            var r = parseFloat($(rateId).val()) || 0;
            $(totalId).val(c > 0 && r > 0 ? '₹' + (c * r).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '');
        });
    }
    calcTotal('#add_scratch_count', '#add_rate', '#add_total');
    calcTotal('#edit_scratch_count', '#edit_rate', '#edit_total');

    // ── Add Modal ──────────────────────────────────────────────────────
    $('#openAddModal').on('click', function () { $('#addPackageModal').removeClass('hidden'); });
    $('#addPackageModal').on('click', function (e) { if (e.target === this) { $(this).addClass('hidden'); $('#addPackageForm')[0].reset(); } });

    $('#addPackageForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("admin.scratch-rate.store") }}',
            type: 'POST',
            data: { scratch_count: $('#add_scratch_count').val(), rate: $('#add_rate').val(), _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.success) {
                    $('#addPackageModal').addClass('hidden');
                    $('#addPackageForm')[0].reset();
                    $('#add_total').val('');
                    showNotification('success', res.message);
                    table.ajax.reload();
                } else { showNotification('error', res.message); }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON?.errors ? Object.values(xhr.responseJSON.errors).flat().join(', ') : (xhr.responseJSON?.message || 'Error occurred.');
                showNotification('error', msg);
            }
        });
    });

    // ── Edit Modal ─────────────────────────────────────────────────────
    $('#closeEditModal, #cancelEdit').on('click', function () { $('#editPackageModal').addClass('hidden'); $('#editPackageForm')[0].reset(); });
    $('#editPackageModal').on('click', function (e) { if (e.target === this) { $(this).addClass('hidden'); } });

    $('#editPackageForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        $.ajax({
            url: '{{ route("admin.scratch-rate.update", ":id") }}'.replace(':id', id),
            type: 'PUT',
            data: { scratch_count: $('#edit_scratch_count').val(), rate: $('#edit_rate').val(), _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.success) {
                    $('#editPackageModal').addClass('hidden');
                    showNotification('success', res.message);
                    table.ajax.reload();
                } else { showNotification('error', res.message); }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON?.errors ? Object.values(xhr.responseJSON.errors).flat().join(', ') : (xhr.responseJSON?.message || 'Error occurred.');
                showNotification('error', msg);
            }
        });
    });

    // ── Delete Modal ───────────────────────────────────────────────────
    $('#cancelDelete').on('click', function () { $('#deleteConfirmModal').addClass('hidden'); deleteId = null; });
    $('#deleteConfirmModal').on('click', function (e) { if (e.target === this) { $(this).addClass('hidden'); deleteId = null; } });

    $('#confirmDelete').on('click', function () {
        if (!deleteId) return;
        $.ajax({
            url: '{{ route("admin.scratch-rate.destroy", ":id") }}'.replace(':id', deleteId),
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                $('#deleteConfirmModal').addClass('hidden');
                if (res.success) { showNotification('success', res.message); table.ajax.reload(); }
                else { showNotification('error', res.message); }
                deleteId = null;
            },
            error: function () { $('#deleteConfirmModal').addClass('hidden'); showNotification('error', 'Delete failed.'); deleteId = null; }
        });
    });

});

// ── Global functions ───────────────────────────────────────────────────
function editPackage(id) {
    $.ajax({
        url: '{{ route("admin.scratch-rate.edit", ":id") }}'.replace(':id', id),
        type: 'GET',
        success: function (res) {
            if (res.success) {
                var p = res.package;
                $('#edit_id').val(p.id);
                $('#edit_scratch_count').val(p.scratch_count);
                $('#edit_rate').val(parseFloat(p.rate).toFixed(2));
                var total = p.scratch_count * parseFloat(p.rate);
                $('#edit_total').val('₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2}));
                $('#editPackageModal').removeClass('hidden');
            } else { showNotification('error', res.message); }
        },
        error: function () { showNotification('error', 'Failed to load package.'); }
    });
}

function deletePackage(id) {
    deleteId = id;
    $('#deleteConfirmModal').removeClass('hidden');
}

function showNotification(type, message) {
    var bg = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    var el = $('<div class="fixed top-4 right-4 ' + bg + ' text-white px-6 py-3 rounded-lg shadow-lg z-50">' + message + '</div>');
    $('body').append(el);
    setTimeout(function () { el.fadeOut(300, function () { $(this).remove(); }); }, 3000);
}
</script>

@endsection
