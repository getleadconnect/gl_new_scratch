@extends('layouts.admin')

<link rel="stylesheet" href="{{asset('assets/css/datatable.css')}}">

<style>
   .bg-light-cyan { background-color: #dcfafcd9; }
</style>

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-2 text-sm text-muted-foreground">Campaigns belonging to your child users</p>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg p-4" style="border: 1px solid #e4e4e4;">
        <div class="flex flex-wrap items-end gap-3">
            <!-- User -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">User</label>
                <select id="filter_user_id" style="width:170px;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                    <option value="">All Users</option>
                    @foreach($childUsers as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
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
            <table id="admin-campaigns-table" class="data-table w-full" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>User</th>
                        <th>Campaign Name</th>
                        <th>Campaign Image</th>
                        <th>Type</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
<!-- DataTables JS -->
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

<script>
$(document).ready(function () {

    var table = $('#admin-campaigns-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: false,
        paging: true,
        pageLength: 50,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: {
            search: '',
            searchPlaceholder: 'Search campaigns...',
        },
        ajax: {
            url: "{{ route('admin.campaigns.data') }}",
            type: 'GET',
            data: function (d) {
                d.filter_user_id   = $('#filter_user_id').val();
                d.filter_status    = $('#filter_status').val();
                d.filter_date_from = $('#filter_date_from').val();
                d.filter_date_to   = $('#filter_date_to').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',    name: 'DT_RowIndex',    orderable: false, searchable: false },
            { data: 'user_name',      name: 'user_name',      searchable: true },
            { data: 'campaign_name',  name: 'campaign_name',  searchable: true },
            { data: 'campaign_image', name: 'campaign_image', orderable: false, searchable: false },
            { data: 'type',           name: 'type',           orderable: false, searchable: false },
            { data: 'end_date',       name: 'end_date',       orderable: false, searchable: false },
            { data: 'status',         name: 'status',         orderable: false, searchable: false },
        ],
    });

    // Apply Filters
    $('#applyFilters').on('click', function () {
        table.ajax.reload();
    });

    // Reset Filters
    $('#resetFilters').on('click', function () {
        $('#filter_user_id').val('');
        $('#filter_status').val('');
        $('#filter_date_from').val('');
        $('#filter_date_to').val('');
        table.ajax.reload();
    });

});
</script>

@endsection
