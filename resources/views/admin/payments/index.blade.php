@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div id="pay-page-header" class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
            <p class="mt-2 text-sm text-muted-foreground">View all payment transactions</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg p-4" style="border:1px solid #e4e4e4;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <div id="pay-filter-fields" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">

                <!-- Status -->
                <div style="width:170px;">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                    <select id="filter-status" style="width:170px;" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                        <option value="">All Status</option>
                        <option value="success">Success</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>

                <!-- Date From -->
                <div style="width:170px;">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Date From</label>
                    <input type="date" id="filter-date-from" style="width:170px;" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                </div>

                <!-- Date To -->
                <div style="width:170px;">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Date To</label>
                    <input type="date" id="filter-date-to" style="width:170px;" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button id="btn-apply-filter"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-slate-900 text-white hover:bg-slate-800 h-9 px-4 py-2">
                        Apply
                    </button>
                    <button id="btn-reset-filter"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        Reset
                    </button>
                    <button id="btn-export-payments" type="button"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export CSV
                    </button>
                </div>

            </div>

            <!-- Total Amount -->
            <div style="text-align:right;">
                <p class="text-xs text-muted-foreground">Total Amount</p>
                <p class="text-2xl font-bold text-primary" id="payTotalAmount">₹0.00</p>
            </div>
        </div>
    </div>

    <!-- DataTable Card -->
    <div id="pay-datatable-wrap">
        <div class="bg-white shadow-sm rounded-lg" style="border:1px solid #e4e4e4;">
            <div class="p-6">
                <table id="payments-table" class="data-table w-full" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Mobile</th>
                            <th>Payment Id</th>
                            <th>Scratch Count</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Mobile responsive overrides -->
<style>
@media (max-width: 640px) {
    #pay-page-header { flex-direction: column; align-items: stretch; gap: 12px; }
    #pay-filter-fields { flex-direction: column; align-items: stretch; }
    #pay-filter-fields > div { width: 100% !important; }
    #pay-filter-fields select,
    #pay-filter-fields input[type="date"] { width: 100% !important; box-sizing: border-box; }
    #pay-filter-fields .flex.gap-2 { flex-direction: column; }
    #btn-apply-filter, #btn-reset-filter, #btn-export-payments { width: 100%; }
    #pay-datatable-wrap .bg-white { overflow-x: auto; }
}
</style>

<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {

    var table = $('#payments-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        paging: true,
        pageLength: 25,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: { search: '', searchPlaceholder: 'Search...' },
        ajax: {
            url: '{{ route("admin.payments.data") }}',
            type: 'GET',
            data: function (d) {
                d.filter_status    = $('#filter-status').val();
                d.filter_date_from = $('#filter-date-from').val();
                d.filter_date_to   = $('#filter-date-to').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',       name: 'DT_RowIndex',       orderable: false, searchable: false },
            { data: 'user_col',          name: 'user_col',          orderable: false },
            { data: 'mobile_col',        name: 'mobile_col',          orderable: false },
            { data: 'razorpay_payment_id', name: 'razorpay_payment_id', orderable: false },
            { data: 'scratch_fmt',       name: 'scratch_count',     orderable: true, searchable: false },
            { data: 'amount_fmt',        name: 'amount',            orderable: true, searchable: false },
            { data: 'currency',          name: 'currency',          orderable: false, searchable: false },
            { data: 'status_col',        name: 'status',            orderable: false, searchable: false },
            { data: 'date_col',          name: 'created_at',        orderable: true,  searchable: false },
        ],
    });

    // Load total amount
    function loadTotal() {
        $.get('{{ route("admin.payments.total") }}', {
            filter_status:    $('#filter-status').val(),
            filter_date_from: $('#filter-date-from').val(),
            filter_date_to:   $('#filter-date-to').val(),
        }, function (res) {
            var total = parseFloat(res.total || 0);
            $('#payTotalAmount').text('\u20B9' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 }));
        });
    }
    loadTotal();

    // Export to CSV
    $('#btn-export-payments').on('click', function (e) {
        e.preventDefault();
        var params = $.param({
            filter_status:    $('#filter-status').val() || '',
            filter_date_from: $('#filter-date-from').val() || '',
            filter_date_to:   $('#filter-date-to').val() || '',
        });
        window.location.href = '{{ route("admin.payments.export") }}?' + params;
    });

    // Filter Apply / Reset
    $('#btn-apply-filter').on('click', function () { table.ajax.reload(); loadTotal(); });
    $('#btn-reset-filter').on('click', function () {
        $('#filter-status').val('');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        table.ajax.reload();
        loadTotal();
    });

});
</script>

@endsection
