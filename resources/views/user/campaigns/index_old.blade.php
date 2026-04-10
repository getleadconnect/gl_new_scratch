@extends('layouts.user')

<!-- DataTable CSS -->
<link rel="stylesheet" href="{{asset('assets/css/datatable.css')}}">

<style>
    /* Modal Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            transform: scale(0.95) translateY(-10px);
            opacity: 0;
        }
        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    #deleteConfirmModal,
    #editCampaignModal,
    #addCampaignModal {
        animation: fadeIn 0.2s ease-out;
    }

    #deleteConfirmModal .animate-in,
    #editCampaignModal .animate-in,
    #addCampaignModal .animate-in {
        animation: slideIn 0.2s ease-out;
    }
</style>

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
            <p class="mt-2 text-sm text-muted-foreground">Manage all your campaigns</p>
        </div>
        <button id="openAddCampaignModal" class="flex items-center px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Campaign
        </button>
    </div>

    <!-- DataTable Wrapper -->
    <div id="datatable-wrapper">
        <!-- DataTable Card -->
        <div id="datatable-card" class="bg-white shadow-sm rounded-lg" style="border: 1px solid #e4e4e4;">
            <div class="p-6">
                <table id="campaigns-table" class="w-full" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Campaign Name</th>
                            <th>Description</th>
                            <th>Date Range</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Info and Pagination will be appended here by DataTables (outside card) -->
    </div>
</div>

<!-- Add Campaign Modal -->
<div id="addCampaignModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 my-8 animate-in">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Add New Campaign</h3>
                <button id="closeAddCampaignModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <form id="addCampaignForm" class="p-6">
            <div class="space-y-4">
                <!-- Campaign Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Name <span class="text-red-500">*</span></label>
                    <input type="text" name="campaign_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" id="cancelAddCampaign" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-slate-900 text-white hover:bg-slate-800 h-10 px-4 py-2">
                    Create Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Campaign Modal -->
<div id="editCampaignModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 my-8 animate-in">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Edit Campaign</h3>
                <button id="closeEditCampaignModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <form id="editCampaignForm" class="p-6">
            <input type="hidden" name="campaign_id" id="editCampaignId">

            <div class="space-y-4">
                <!-- Campaign Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Name <span class="text-red-500">*</span></label>
                    <input type="text" name="campaign_name" id="editCampaignName" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="editDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" id="editStartDate" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" id="editEndDate" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="editStatus" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" id="cancelEditCampaign" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-slate-900 text-white hover:bg-slate-800 h-10 px-4 py-2">
                    Update Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 animate-in">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Delete Campaign</h3>
                    <p class="mt-2 text-sm text-gray-500">Are you sure you want to delete this campaign? This action cannot be undone.</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end rounded-b-lg">
            <button id="cancelDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                Cancel
            </button>
            <button id="confirmDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 py-2">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- jQuery (required for DataTables) -->
<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>

<!-- DataTables JS -->
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

<script>
$(document).ready(function() {
    var table = $('#campaigns-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        paging: true,
        pageLength: 50,
        pagingType: "simple_numbers",
        lengthChange: true,
        language: {
            search: '',
            searchPlaceholder: 'Search campaigns...',
        },
        ajax: {
            url: "{{ route('user.campaigns.data') }}",
            type: 'GET'
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'campaign_name',
                name: 'campaign_name'
            },
            {
                data: 'description',
                name: 'description',
                orderable: false
            },
            {
                data: 'date_range',
                name: 'date_range',
                orderable: false
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'created_date',
                name: 'created_at'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
    });

    // Open Add Campaign Modal
    $('#openAddCampaignModal').on('click', function() {
        $('#addCampaignModal').removeClass('hidden');
    });

    // Close Add Campaign Modal
    $('#closeAddCampaignModal, #cancelAddCampaign').on('click', function() {
        $('#addCampaignModal').addClass('hidden');
        $('#addCampaignForm')[0].reset();
    });

    // Close modal on background click
    $('#addCampaignModal').on('click', function(e) {
        if (e.target === this) {
            $(this).addClass('hidden');
            $('#addCampaignForm')[0].reset();
        }
    });

    // Handle Add Campaign Form Submission
    $('#addCampaignForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            campaign_name: $('input[name="campaign_name"]').val(),
            description: $('textarea[name="description"]').val(),
            start_date: $('input[name="start_date"]').val(),
            end_date: $('input[name="end_date"]').val(),
            status: $('select[name="status"]').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("user.campaigns.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#addCampaignModal').addClass('hidden');
                    $('#addCampaignForm')[0].reset();
                    showNotification('success', response.message);
                    $('#campaigns-table').DataTable().ajax.reload();
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while creating the campaign.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showNotification('error', errorMessage);
            }
        });
    });
});

// Edit campaign function
function editCampaign(campaignId) {
    $.ajax({
        url: '{{ route("user.campaigns.edit", ":id") }}'.replace(':id', campaignId),
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const campaign = response.campaign;

                $('#editCampaignId').val(campaign.id);
                $('#editCampaignName').val(campaign.campaign_name);
                $('#editDescription').val(campaign.description);
                $('#editStartDate').val(campaign.start_date.split('T')[0]);
                $('#editEndDate').val(campaign.end_date.split('T')[0]);
                $('#editStatus').val(campaign.status);

                $('#editCampaignModal').removeClass('hidden');
            } else {
                showNotification('error', response.message);
            }
        },
        error: function(xhr) {
            showNotification('error', 'Failed to fetch campaign data.');
        }
    });
}

// Close Edit Campaign Modal
$('#closeEditCampaignModal, #cancelEditCampaign').on('click', function() {
    $('#editCampaignModal').addClass('hidden');
    $('#editCampaignForm')[0].reset();
});

// Close edit modal on background click
$('#editCampaignModal').on('click', function(e) {
    if (e.target === this) {
        $(this).addClass('hidden');
        $('#editCampaignForm')[0].reset();
    }
});

// Handle Edit Campaign Form Submission
$('#editCampaignForm').on('submit', function(e) {
    e.preventDefault();

    const campaignId = $('#editCampaignId').val();

    const formData = {
        campaign_name: $('#editCampaignName').val(),
        description: $('#editDescription').val(),
        start_date: $('#editStartDate').val(),
        end_date: $('#editEndDate').val(),
        status: $('#editStatus').val(),
        _token: '{{ csrf_token() }}'
    };

    $.ajax({
        url: '{{ route("user.campaigns.update", ":id") }}'.replace(':id', campaignId),
        type: 'PUT',
        data: formData,
        success: function(response) {
            if (response.success) {
                $('#editCampaignModal').addClass('hidden');
                $('#editCampaignForm')[0].reset();
                showNotification('success', response.message);
                $('#campaigns-table').DataTable().ajax.reload();
            } else {
                showNotification('error', response.message);
            }
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred while updating the campaign.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMessage = Object.values(errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification('error', errorMessage);
        }
    });
});

// Delete campaign function with modal
let deleteCampaignId = null;

function deleteCampaign(campaignId) {
    deleteCampaignId = campaignId;
    $('#deleteConfirmModal').removeClass('hidden');
}

// Cancel delete
$('#cancelDelete').on('click', function() {
    $('#deleteConfirmModal').addClass('hidden');
    deleteCampaignId = null;
});

// Close modal on background click
$('#deleteConfirmModal').on('click', function(e) {
    if (e.target === this) {
        $(this).addClass('hidden');
        deleteCampaignId = null;
    }
});

// Confirm delete
$('#confirmDelete').on('click', function() {
    if (deleteCampaignId) {
        $.ajax({
            url: '{{ route("user.campaigns.destroy", ":id") }}'.replace(':id', deleteCampaignId),
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteConfirmModal').addClass('hidden');

                if (response.success) {
                    showNotification('success', response.message);
                    $('#campaigns-table').DataTable().ajax.reload();
                } else {
                    showNotification('error', response.message);
                }
                deleteCampaignId = null;
            },
            error: function(xhr) {
                $('#deleteConfirmModal').addClass('hidden');
                showNotification('error', 'An error occurred while deleting the campaign.');
                deleteCampaignId = null;
            }
        });
    }
});

// Simple notification function
function showNotification(type, message) {
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const notification = $(`
        <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-in">
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
</script>

@endsection
