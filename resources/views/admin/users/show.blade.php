@extends('layouts.admin')

<link rel="stylesheet" href="{{asset('assets/css/datatable.css')}}">

<style>


    /* Card styling */
    .profile-card {
        border: 1px solid #e4e4e4;
        border-radius: 8px;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #585656 0%, #a1a0a1 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
        color: white;
        margin-right:50px;
    }

    .info-row {
        display: flex;
        padding: 8px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 500;
        color: #6b7280;
        width: 140px;
        flex-shrink: 0;
    }

    .info-value {
        color: #111827;
        font-weight: 400;
    }

    /* DataTables styling */
    #scratch-history-table {
        width: 100% !important;
        border-collapse: collapse;
        border: 1px solid #e4e4e4;
    }

    #scratch-history-table thead th {
        background-color: #f9fafb;
        color: #3e3e3e;
        font-weight: 500;
        font-size: 14px;
        padding: 12px 16px;
        text-align: left;
        border: 1px solid #e4e4e4;
    }

    #scratch-history-table tbody td {
        padding: 14px 16px;
        color: #374151;
        font-size: 14px;
        border: 1px solid #e4e4e4;
    }

    #scratch-history-table tbody tr:hover {
        background-color: #f9fafb;
    }
    .text-semi-red
    {
        color:#ff0000b8;
    }


</style>

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
            </div>
            <p class="mt-2 text-sm text-muted-foreground">View and manage user details</p>
        </div>
        <div class="text-right">
            <div class="text-4xl font-bold" style="color:#000;">
                {{ $scratch_count ?? 0 }}
            </div>
            <p class="text-sm text-gray-500 mt-1">Total Scratch Balance</p>
        </div>
    </div>

    <!-- Two Column Layout: 25% - 75% -->
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Left Column (25%) -->
        <div class="w-full lg:w-[35%] space-y-4">
            <!-- User Profile Card -->
            <div class="profile-card p-6">
                <div class="flex items-left gap-4 mb-6">
                    <div class="profile-avatar flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div style="margin:auto 0">
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $user->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mobile:</span>
                        <span class="info-value">{{ $user->country_code }} {{ $user->mobile }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Company:</span>
                        <span class="info-value">{{ $user->company_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value">{{ $user->address ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            @php
                                $subscription_date = \Carbon\Carbon::create($user->subscription_end_date)->addDays(1)->format('Y-m-d');
                                $isExpired = $subscription_date <= date('Y-m-d');
                            @endphp
                            @if($user->subscription_start_date==null)
                                <span class="text-semi-red font-medium">Subscription not found.!</span>
                            @else
                                @if($isExpired)
                                    <span class="text-red-600 font-medium">Expired</span>
                                @elseif($user->status == 1)
                                    <span class="text-green-600 font-medium">Active</span>
                                @else
                                    <span class="text-gray-600 font-medium">Inactive</span>
                                @endif
                            @endif

                        </span>
                    </div>

                    @if($user_role_id==1)
                        <div class="info-row" style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex;">
                                <span class="info-label">No of child users:</span>
                                <span class="info-value font-medium font-weight-900">{{ $child_users_count }}</span>
                            </div>
                            <a href="{{ route('admin.sub-users.index', $user->id) }}" class="inline-flex items-center justify-center rounded-md text-xs font-medium text-white hover:opacity-90" style="background:#18181b; padding: 5px 12px; white-space: nowrap;">
                                View Child Users
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Subscription Period Card -->
            <div class="profile-card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Subscription Period</h3>
                <div class="space-y-3">
                    <div class="info-row">
                        <span class="info-label">Start Date:</span>
                        @if($user->subscription_start_date!=null)
                        <span class="info-value">{{ \Carbon\Carbon::parse($user->subscription_start_date)->format('d-m-Y') }}</span>
                        @else
                        <span class="info-value"> -- </span>
                        @endif
                    </div>
                    <div class="info-row">
                        <span class="info-label">End Date:</span>
                        @if($user->subscription_end_date!=null)
                        <span class="info-value" style="{{ $isExpired ? 'color: red;' : '' }}">
                            {{ \Carbon\Carbon::parse($user->subscription_end_date)->format('d-m-Y') }}
                        </span>
                        @else
                        <span class="info-value"> -- </span>
                        @endif
                    </div>
                    <div class="info-row">
                        <span class="info-label">Days Left:</span>
                        <span class="info-value">
                            @if($user->subscription_end_date!=null)
                            @php
                                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($user->subscription_end_date), false);
                            @endphp
                            <span class="{{ $daysLeft <= 0 ? 'text-semi-red' : ($daysLeft <= 7 ? 'text-orange-600' : 'text-green-600') }} font-medium">
                                {{ $daysLeft > 0 ? round($daysLeft,2) . ' days' : 'Expired' }}
                            </span>
                            @else
                            <span class="text-semi-red font-medium">
                                0 days
                            </span>
                            @endif

                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (75%) -->
        <div class="w-full lg:w-[65%] space-y-4">
            <!-- First Row: Two Columns (50% - 50%) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Add Subscription Period Card -->
                <div class="profile-card p-6">
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Subscription Period</h3>
                    <form id="subscriptionForm">
                        @csrf
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                    <input type="date" name="subscription_start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                    <input type="date" name="subscription_end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-slate-900 text-white hover:bg-slate-800 h-10 px-4 py-2" style="width: 175px;">
                                    Update Subscription
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Add Scratch Count Card -->
                 @if($user_role_id!=1)
                <div class="profile-card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Add Scratch Credits</h3>
                    <form id="scratchForm">
                        @csrf
                        <div class="space-y-4">
                            <div class="flex gap-3 items-end">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Scratch Credits</label>
                                    <input type="number" name="scratch_count" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter count">
                                </div>
                                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-slate-900 text-white hover:bg-slate-800 h-10 px-4" style="width: 175px;">
                                    Add Scratch
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            @if($user_role_id!=1)
            <!-- Scratch Count Stats -->
            <div class="profile-card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Scratch Credits Overview</h3>
                <div style="display: flex; gap: 16px;">
                    <div style="flex: 1; text-align: center; padding: 16px; border-radius: 8px; background-color: #f0f5ff; border: 1px solid #dbeafe;">
                        <p style="font-size: 14px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">Total Scratch</p>
                        <p style="font-size: 30px; font-weight: 700; color: #3b82f6;">{{ $total_scratch }}</p>
                    </div>
                    <div style="flex: 1; text-align: center; padding: 16px; border-radius: 8px; background-color: #fef3f2; border: 1px solid #fecaca;">
                        <p style="font-size: 14px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">Used Scratch</p>
                        <p style="font-size: 30px; font-weight: 700; color: #ef4444;">{{ $used_scratch }}</p>
                    </div>
                    <div style="flex: 1; text-align: center; padding: 16px; border-radius: 8px; background-color: #ecfdf5; border: 1px solid #bbf7d0;">
                        <p style="font-size: 14px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">Balance Scratch</p>
                        <p style="font-size: 30px; font-weight: 700; color: #22c55e;">{{ $balance_scratch }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Second Row: Full Width (100%) Purchase History -->
            <div class="profile-card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Scratch Purchase History</h3>
                <div class="overflow-x-auto">
                    <table id="scratch-history-table" class="w-full">
                        <thead>
                            <tr>
                                <th>SlNo</th>
                                <th>Date</th>
                                <th>Narration</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (required for DataTables) -->
<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>

<!-- DataTables JS -->
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

<script>
$(document).ready(function() {
    const userId = {{ $user->id }};

    // Initialize DataTable for scratch history
    $('#scratch-history-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.users.scratchHistory", $user->id) }}',
            type: 'GET'
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            { data: 'date', name: 'date' },
            { data: 'narration', name: 'narration' },
            { data: 'scratch_count', name: 'scratch_count' },
        ],
        pageLength: 10
    });



    // Handle subscription form submission
    $('#subscriptionForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            subscription_start_date: $('input[name="subscription_start_date"]').val(),
            subscription_end_date: $('input[name="subscription_end_date"]').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("admin.users.addSubscription", $user->id) }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    // Reload page to show updated subscription
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr) {
                showNotification('error', 'Failed to update subscription.');
            }
        });
    });

    // Handle scratch form submission
    $('#scratchForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            scratch_count: $('input[name="scratch_count"]').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("admin.users.addScratch", $user->id) }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    // Reset form
                    $('#scratchForm')[0].reset();
                    // Reload DataTable
                    $('#scratch-history-table').DataTable().ajax.reload();
                    // Reload page to update scratch count
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr) {
                showNotification('error', 'Failed to add scratch count.');
            }
        });
    });

    // Simple notification function
    function showNotification(type, message) {
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const notification = $(`
            <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50">
                ${message}
            </div>
        `);

        $('body').append(notification);

        setTimeout(function() {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
});
</script>

@endsection
