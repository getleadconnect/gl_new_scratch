@extends('layouts.admin')

<link rel="stylesheet" href="{{asset('assets/css/datatable.css')}}">

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
            <p class="mt-2 text-sm text-muted-foreground">View purchase history of users (last 3 months by default)</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg p-4" style="border:1px solid #e4e4e4;">
        <div id="filter-fields" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">

            <!-- User -->
            <div style="width:220px;">
                <label class="block text-xs font-medium text-gray-600 mb-1">User</label>
                <select id="filter-user" style="width:220px;" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">All Users</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">
                            {{ strtoupper($u->name) }} ({{ $u->role_id == 2 ? 'User' : 'Child' }})
                        </option>
                    @endforeach
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
                <button id="btn-apply-filter" class="h-9 px-4 text-sm font-medium rounded-md text-white" style="background:#18181b;border:none;cursor:pointer;white-space:nowrap;">
                    Apply
                </button>
                <button id="btn-reset-filter" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2" >
                    Reset
                </button>
                <button id="btn-export-csv" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export CSV
                </button>
            </div>
        </div>
    </div>


    <!-- DataTable Card -->
    <div class="bg-white shadow-sm rounded-lg" style="border:1px solid #e4e4e4;">

        <div class="p-6">
        <p style="padding-bottom:10px;color:#5454bd;">*: To display last 3 months purchase list only. </p>

            <table id="purchase-history-table" class="data-table w-full" style="width:100%">
                <thead>
                    <tr>
                        <th>SlNo</th>
                        <th>Unique ID</th>
                        <th>User</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th>Narration</th>
                        <th>Count</th>
                        <th style="text-align:right;">Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery + DataTables -->
<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

<script>
$(document).ready(function() {
    var table = $('#purchase-history-table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 50,
        pagingType: 'simple_numbers',
        language: {
            search: '',
            searchPlaceholder: 'Search...'
        },
        ajax: {
            url: "{{ route('admin.purchase-history.data') }}",
            type: 'GET',
            data: function(d) {
                d.filter_user_id   = $('#filter-user').val();
                d.filter_date_from = $('#filter-date-from').val();
                d.filter_date_to   = $('#filter-date-to').val();
            }
        },

        columns: [
            { data: 'DT_RowIndex',    name: 'DT_RowIndex',    orderable: false, searchable: false },
            { data: 'user_unique_id', name: 'user_unique_id', orderable: false, searchable: false },
            { data: 'user_name',      name: 'user_name',      orderable: false, searchable: false },
            { data: 'mobile',         name: 'mobile',         orderable: false, searchable: false },
            { data: 'role',           name: 'role',           orderable: false, searchable: false },
            { data: 'narration',      name: 'narration',      orderable: false, searchable: false },
            { data: 'scratch_count',  name: 'scratch_count',  orderable: false, searchable: false },
            { data: 'amount',         name: 'amount',         orderable: false, searchable: false },
            { data: 'purchase_date',  name: 'created_at',     orderable: false, searchable: false },
        ]
    });

    $('#btn-apply-filter').on('click', function() {
        table.ajax.reload();
    });

    $('#btn-export-csv').on('click', function() {
        var params = $.param({
            filter_user_id:   $('#filter-user').val(),
            filter_date_from: $('#filter-date-from').val(),
            filter_date_to:   $('#filter-date-to').val()
        });
        window.location.href = "{{ route('admin.purchase-history.export') }}" + '?' + params;
    });

    $('#btn-reset-filter').on('click', function() {
        $('#filter-user').val('');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        table.ajax.reload();
    });
});
</script>

@endsection
