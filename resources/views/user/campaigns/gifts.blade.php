@extends('layouts.user')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

<style>
    .no-image-placeholder {
        width: 60px;
        height: 60px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #9ca3af;
    }
    #image-preview {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        display: none;
    }
.border{
    border:1px solid #c4c4c4 !important;
}
</style>

@section('content')

<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-foreground">Add Gifts</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('user.campaigns.index') }}"
               class="inline-flex items-center px-4 py-2 bg-primary text-primary-foreground text-sm font-medium rounded-md hover:bg-primary/90 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
            <a href="{{ route('user.campaigns.index') }}"
               class="inline-flex items-center px-4 py-2 bg-primary text-primary-foreground text-sm font-medium rounded-md hover:bg-primary/90 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                View Campaigns
            </a>
        </div>
    </div>


<!-- Campaign Info Card -->
    <div class="bg-white rounded-lg border border-border shadow-sm p-5">
        <div class="flex items-start justify-between">
            <div class="space-y-1">
                <p class="text-sm font-semibold text-foreground">Campaign: <span class="font-bold">{{ $campaign->campaign_name }}</span></p>
                <p class="text-sm font-semibold text-foreground">Type: <span class="font-bold">{{ $type }}</span></p>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium text-muted-foreground">Available Scratch Count:</p>
                <p class="text-2xl font-bold " id="scratchCount" >{{ $scratchCount }}</p>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="flex gap-5 items-start">

        <!-- Left Column: 30% — Campaign Info + Add Gift Form -->
        <div class="w-[30%] flex-shrink-0 space-y-4">

            <!-- Add Gift Form Card -->
            <div class="bg-white rounded-lg border border-border shadow-sm p-5">
                <h2 class="text-base font-semibold text-foreground mb-4">Add Gift :</h2>

                <form id="addGiftForm" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">

                        <!-- Gift Count -->
                        <div>
                            <label class="block text-xs font-medium text-muted-foreground mb-1">Gift Count</label>
                            <input type="number" name="gift_count" id="gift_count" min="1"  required
                                   class="w-full h-9 px-3 py-1 text-sm border border-input rounded-md bg-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-medium text-muted-foreground mb-1">Description</label>
                            <textarea name="description" id="description" rows="3"  required
                                      class="w-full px-3 py-1 text-sm border border-input rounded-md bg-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 resize-none"></textarea>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-medium text-muted-foreground mb-1">Status</label>
                            <select name="status" id="status"  required
                                    class="w-full h-9 px-3 py-1 text-sm border border-input rounded-md bg-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                <option value="">--select--</option>
                                <option value="1">Win</option>
                                <option value="0">Loss</option>
                            </select>
                        </div>

                        <!-- Image -->
                        <div>
                            <label class="block text-xs font-medium text-muted-foreground mb-1">Image (Max: 500mb, Size 450x450)</label>
                            <input type="file" name="image" id="image" accept="image/*"  required
                                   class="w-full text-sm text-muted-foreground file:mr-2 file:py-1 file:px-3 file:rounded file:border file:border-input file:text-xs file:font-medium file:bg-background file:text-foreground hover:file:bg-accent cursor-pointer">
                        </div>

                        <!-- Image Preview -->
                        <div class="flex items-center gap-3">
                            <div class="no-image-placeholder" id="no-image-text">No Image</div>
                            <img id="image-preview" src="" alt="Preview">
                        </div>

                        <!-- Add Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-2 bg-primary text-primary-foreground text-sm font-medium rounded-md hover:bg-primary/90 transition-colors">
                                Add
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <!-- Right Column: 70% — Gifts DataTable -->
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded-lg border border-border shadow-sm p-5">
                <h2 class="text-base font-semibold text-foreground mb-4">Gifts List :</h2>

                <table id="gifts-table" class="data-table w-full" style="width:100%">
                    <thead>
                        <tr>
                            <th>SINo</th>
                            <th>Image</th>
                            <th>Count</th>
                            <th>Stage</th>
                            <th>Description</th>
                            <th>Balance</th>
                            <th>Win/Loss</th>
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

<!-- Edit Gift Modal -->
<div id="editGiftModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-border">
            <h3 class="text-base font-semibold text-foreground">Edit Gift</h3>
            <button id="closeEditGiftModal" class="text-muted-foreground hover:text-foreground">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editGiftForm" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <input type="hidden" name="editgiftId" id="editGiftId">
            <div>
                <label class="block text-sm font-medium text-foreground mb-1">Gift Count <span class="text-red-500">*</span></label>
                <input type="number" id="editGiftCount" name="gift_count" min="1" required
                       class="w-full h-9 px-3 text-sm border border-input rounded-md bg-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-foreground mb-1">Description</label>
                <textarea id="editDescription" name="description" rows="3"
                          class="w-full px-3 py-2 text-sm border border-input rounded-md bg-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-foreground mb-1">Status <span class="text-red-500">*</span></label>
                <select id="editStatus" name="status" required
                        class="w-full h-9 px-3 text-sm border border-input rounded-md bg-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                    <option value="1">Win</option>
                    <option value="0">Loss</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-foreground mb-1">Image (optional)</label>
                <input type="file" name="image" id="editImage" accept="image/*"
                       class="w-full text-sm text-muted-foreground file:mr-2 file:py-1 file:px-3 file:rounded file:border file:border-input file:text-xs file:font-medium file:bg-background file:text-foreground hover:file:bg-accent cursor-pointer">
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" id="cancelEditGift"
                        class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent hover:text-accent-foreground transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium bg-slate-900 text-white rounded-md hover:bg-slate-800 transition-colors">
                    Update Gift
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteGiftModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-sm mx-4">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-foreground">Delete Gift</h3>
                    <p class="mt-1 text-sm text-muted-foreground">Are you sure you want to delete this gift? This action cannot be undone.</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end rounded-b-lg">
            <button id="cancelDeleteGift"
                    class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent hover:text-accent-foreground transition-colors">
                Cancel
            </button>
            <button id="confirmDeleteGift"
                    class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<!-- DataTables JS -->
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
const campaignId = {{ $campaign->id }};

$(document).ready(function () {

    // Init DataTable
    var table = $('#gifts-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        paging: true,
        pageLength: 50,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: {
            search: '',
            searchPlaceholder: 'Search gifts...',
        },
        ajax: {
            url: "{{ route('user.campaigns.gifts.data', $campaign->id) }}",
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image',       name: 'image',       orderable: false, searchable: false },
            { data: 'gift_count',  name: 'gift_count',  searchable: true },
            { data: 'stage',       name: 'stage',       orderable: false, searchable: false },
            { data: 'description', name: 'description', orderable: false, searchable: true },
            { data: 'balance_count', name: 'balance_count', searchable: true },
            { data: 'win_loss',    name: 'win_loss',    orderable: false, searchable: false },
            { data: 'status',      name: 'status',      searchable: false },
            { data: 'action',      name: 'action',      orderable: false, searchable: false },
        ],
        
    });

    // Image preview
    $('#image').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#no-image-text').hide();
                $('#image-preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#image-preview').hide();
            $('#no-image-text').show();
        }
    });

    // Add Gift Submit
    $('#addGiftForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        var scratch_count=parseInt($("#scratchCount").html());
        var giftCount=parseInt($("#gift_count").val());

        if(scratch_count<giftCount)
        {
            showNotification('error', 'Insuficiant scratch credits.!');
        }
        else
        {
            $.ajax({
                url: "{{ route('user.campaigns.gifts.store', $campaign->id) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        $('#addGiftForm')[0].reset();
                        $('#image-preview').hide();
                        $('#no-image-text').show();
                        table.ajax.reload();
                        showNotification('success', response.message);
                    } else {
                        showNotification('error', response.message);
                    }
                },
                error: function (xhr) {
                    const msg = xhr.responseJSON?.message || 'Failed to add gift.';
                    showNotification('error', msg);
                }
            });
        }
    });

});

// Edit Gift
function editGift(id) {
    $.get("{{ route('user.campaigns.gifts.edit', [$campaign->id, ':id']) }}".replace(':id', id), function (response) {
        if (response.success) {
            const g = response.gift;
            $('#editGiftId').val(g.id);
            $('#editGiftCount').val(g.gift_count);
            $('#editDescription').val(g.description);
            $('#editStatus').val(g.status);
            $('#editGiftModal').removeClass('hidden');
        } else {
            showNotification('error', response.message);
        }
    });
}

$('#closeEditGiftModal, #cancelEditGift').on('click', function () {
    $('#editGiftModal').addClass('hidden');
    $('#editGiftForm')[0].reset();
});

$('#editGiftForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#editGiftId').val();
    const formData = new FormData(this);
    formData.append('_method', 'PUT');

    $.ajax({
        url: "{{ route('user.campaigns.gifts.update', [$campaign->id, ':id']) }}".replace(':id', id),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.success) {
                $('#editGiftModal').addClass('hidden');
                $('#editGiftForm')[0].reset();
                $('#gifts-table').DataTable().ajax.reload();
                showNotification('success', response.message);
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showNotification('error', response.message);
            }
        },
        error: function (xhr) {
            showNotification('error', xhr.responseJSON?.message || 'Failed to update gift.');
        }
    });
});

// Delete Gift
let deleteGiftId = null;

function deleteGift(id) {
    deleteGiftId = id;
    $('#deleteGiftModal').removeClass('hidden');
}

$('#cancelDeleteGift').on('click', function () {
    $('#deleteGiftModal').addClass('hidden');
    deleteGiftId = null;
});

$('#confirmDeleteGift').on('click', function () {
    if (!deleteGiftId) return;

    $.ajax({
        url: "{{ route('user.campaigns.gifts.destroy', [$campaign->id, ':id']) }}".replace(':id', deleteGiftId),
        type: 'POST',
        data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
        success: function (response) {
            $('#deleteGiftModal').addClass('hidden');
            deleteGiftId = null;
            if (response.success) {
                $('#gifts-table').DataTable().ajax.reload();
                showNotification('success', response.message);

                setTimeout(() => {
                    location.reload();
                }, 500);

            } else {
                showNotification('error', response.message);
            }
        },
        error: function (xhr) {
            $('#deleteGiftModal').addClass('hidden');
            showNotification('error', xhr.responseJSON?.message || 'Failed to delete gift.');
        }
    });
});

function showNotification(type, message) {
    const bg = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const el = $(`<div class="fixed top-4 right-4 ${bg} text-white px-6 py-3 rounded-lg shadow-lg z-[9999] text-sm">${message}</div>`);
    $('body').append(el);
    setTimeout(() => el.fadeOut(300, () => el.remove()), 3000);
}
</script>

@endsection
