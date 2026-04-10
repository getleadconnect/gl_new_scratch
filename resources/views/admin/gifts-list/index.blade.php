@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-2 text-sm text-muted-foreground">Gifts belonging to your child users</p>
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

                <!-- User -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">User</label>
                    <select id="filter_user_id" style="min-width:170px;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                        <option value="">All Users</option>
                        @foreach($childUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Campaign -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Campaign</label>
                    <select id="filter_campaign_id" style="min-width:200px;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                        <option value="">All Campaigns</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Status</label>
                    <select id="filter_status" style="min-width:150px;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button id="btn-filter"
                        style="padding:6px 18px;background:#18181b;color:#fff;border:none;border-radius:6px;font-size:13px;cursor:pointer;">
                        Filter
                    </button>
                    <button id="btn-clear"
                        style="padding:6px 18px;background:#f3f4f6;color:#374151;border:1px solid #d1d5db;border-radius:6px;font-size:13px;cursor:pointer;">
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
            <table id="admin-gifts-table" class="data-table w-full" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>User</th>
                        <th>Campaign</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Gift Count</th>
                        <th>Balance</th>
                        <th>Win</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {

    var table = $('#admin-gifts-table').DataTable({
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
            url: "{{ route('admin.gifts-list.data') }}",
            type: 'GET',
            data: function (d) {
                d.filter_user_id     = $('#filter_user_id').val();
                d.filter_campaign_id = $('#filter_campaign_id').val();
                d.filter_status      = $('#filter_status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',   name: 'DT_RowIndex',   orderable: false, searchable: false },
            { data: 'user_name',     name: 'user_name',     orderable: false, searchable: false },
            { data: 'campaign_name', name: 'campaign_name', orderable: false, searchable: false },
            { data: 'image_col',     name: 'image_col',     orderable: false, searchable: false },
            { data: 'description',   name: 'description',   searchable: true },
            { data: 'gift_count',    name: 'gift_count',    searchable: false },
            { data: 'balance_count', name: 'balance_count', searchable: false },
            { data: 'win_loss_col',  name: 'win_loss_col',  orderable: false, searchable: false },
            { data: 'status_col',    name: 'status_col',    orderable: false, searchable: false },
        ],
    });

    // Filter
    $('#btn-filter').on('click', function () {
        table.ajax.reload();
    });

    // Clear
    $('#btn-clear').on('click', function () {
        $('#filter_user_id').val('');
        $('#filter_campaign_id').val('');
        $('#filter_status').val('');
        table.ajax.reload();
    });

});
</script>

@endsection
