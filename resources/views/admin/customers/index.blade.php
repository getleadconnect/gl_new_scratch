@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-2 text-sm text-muted-foreground">All customers who participated in your child users' campaigns</p>
    </div>

    <!-- Filters Card -->
    <div class="bg-white shadow-sm rounded-lg" style="border: 1px solid #e4e4e4;">
        <div class="p-4">
            <div class="flex flex-wrap items-end gap-3">

                <!-- User -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">User</label>
                    <select id="filter-user" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary" style="min-width:170px;">
                        <option value="">All Users</option>
                        @foreach($childUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Campaign -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Campaign</label>
                    <select id="filter-campaign" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary" style="min-width:180px;">
                        <option value="">All Campaigns</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Win Status -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Win Status</label>
                    <select id="filter-win" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary" style="min-width:150px;">
                        <option value="">All Status</option>
                        <option value="1">Win</option>
                        <option value="0">Loss</option>
                    </select>
                </div>

                <!-- Redeem Status -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Redeem Status</label>
                    <select id="filter-redeem" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary" style="min-width:150px;">
                        <option value="">All Status</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Date From -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Date From</label>
                    <input type="date" id="filter-date-from"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                <!-- Date To -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Date To</label>
                    <input type="date" id="filter-date-to"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button id="btn-filter"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        style="background:#18181b;color:#fff;">
                        Filter
                    </button>
                    <button id="btn-clear"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        style="background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;">
                        Clear
                    </button>
                    <button id="btn-export"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium border border-input bg-background text-foreground hover:bg-accent hover:text-accent-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Export Excel
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- DataTable Card -->
    <div class="bg-white shadow-sm rounded-lg" style="border: 1px solid #e4e4e4;">
        <div class="p-6">
            <table id="admin-customers-table" class="data-table w-full" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>User</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Unique Id</th>
                        <th>Campaign</th>
                        <th>Offer</th>
                        <th>Branch</th>
                        <th>Bill No</th>
                        <th>Win</th>
                        <th>Date</th>
                        <th>Redeem</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<!-- DataTables JS -->
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {

    var table = $('#admin-customers-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: false,
        paging: true,
        pageLength: 50,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: {
            search: '',
            searchPlaceholder: 'Search customers...',
        },
        ajax: {
            url: "{{ route('admin.customers.data') }}",
            type: 'GET',
            data: function (d) {
                d.filter_user_id  = $('#filter-user').val();
                d.campaign_id     = $('#filter-campaign').val();
                d.win_status      = $('#filter-win').val();
                d.redeem_status   = $('#filter-redeem').val();
                d.date_from       = $('#filter-date-from').val();
                d.date_to         = $('#filter-date-to').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',     name: 'DT_RowIndex',     orderable: false, searchable: false },
            { data: 'user_name',       name: 'user_name',       orderable: false, searchable: false },
            { data: 'customer_name',   name: 'customer_name',   searchable: true },
            { data: 'customer_mobile', name: 'customer_mobile', searchable: true },
            { data: 'unique_id',       name: 'unique_id',       searchable: true },
            { data: 'campaign_name',   name: 'campaign_name',   orderable: false, searchable: true },
            { data: 'offer',           name: 'offer',           searchable: true },
            { data: 'branch_name',     name: 'branch_name',     orderable: false, searchable: true },
            { data: 'bill_no',         name: 'bill_no',         searchable: true },
            { data: 'win_status',      name: 'win_status',      orderable: false, searchable: false },
            { data: 'created_date',    name: 'created_at',      searchable: false },
            { data: 'redeemed',        name: 'redeemed',        orderable: false, searchable: false },
        ],
    });

    // Filter button
    $('#btn-filter').on('click', function () {
        table.ajax.reload();
    });

    // Clear button
    $('#btn-clear').on('click', function () {
        $('#filter-user').val('');
        $('#filter-campaign').val('');
        $('#filter-win').val('');
        $('#filter-redeem').val('');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        table.ajax.reload();
    });

    // Export Excel button
    $('#btn-export').on('click', function () {
        var params = new URLSearchParams({
            filter_user_id: $('#filter-user').val(),
            campaign_id:    $('#filter-campaign').val(),
            win_status:     $('#filter-win').val(),
            redeem_status:  $('#filter-redeem').val(),
            date_from:      $('#filter-date-from').val(),
            date_to:        $('#filter-date-to').val(),
        });
        window.location.href = "{{ route('admin.customers.export') }}?" + params.toString();
    });

});
</script>

@endsection
