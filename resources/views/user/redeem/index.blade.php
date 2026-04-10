@extends('layouts.user')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-1 text-sm text-muted-foreground">Search by Unique ID to view and redeem customer scratch</p>
    </div>

    <!-- Search Card -->
    <div class="bg-white shadow-sm rounded-lg" style="border: 1px solid #e4e4e4;">
        <div class="px-5 py-4  border-gray-100 flex items-center gap-2" style="border:1px solid #e4e4e4;">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
            </svg>
            <span class="text-sm font-semibold text-gray-700">Search</span>
        </div>
        <div class="p-5">
            <div class="flex items-center gap-3" id="search-row">
                <input type="text" id="search-unique-id"
                    placeholder="Enter Unique ID..."
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary"
                    style="min-width:260px;"
                    autocomplete="off">

                <button id="btn-search"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                    style="background:#18181b;color:#fff;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
                    </svg>
                    Search
                </button>

                <button id="btn-clear-search"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                    style="background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;">
                    Clear
                </button>
            </div>

            <!-- Error Message -->
            <div id="search-error" class="hidden mt-3 px-4 py-2 rounded-md text-sm"
                style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;"></div>
        </div>
    </div>

    <!-- Customer Details Card (hidden until search) -->
    <div id="customer-details-card" class="hidden bg-white shadow-sm rounded-lg" style="border: 1px solid #e4e4e4;">
        <div class="px-5 py-4  border-gray-100 flex items-center gap-2" style="border: 1px solid #e4e4e4;">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-sm font-semibold text-gray-700">Customer Details</span>

            <!-- Already Redeemed Badge -->
            <span id="redeemed-badge" class="hidden ml-auto text-xs font-semibold px-2.5 py-0.5 rounded-full"
                style="background:#dcfce7;color:#166534;">
                Redeemed
            </span>
        </div>

        <div class="p-6">
            <div class="flex items-start justify-between gap-8" id="details-inner">

                <!-- Details Table -->
                <div class="flex-1">
                    <table class="w-full text-sm" style="border-collapse:collapse;">
                        <tbody>
                            <tr style="border-bottom:1px solid #e4e4e4;">
                                <td class="py-2.5 pr-4 font-medium text-gray-600 w-28">Campaign</td>
                                <td class="py-2.5 text-gray-400 w-4">:</td>
                                <td class="py-2.5 text-gray-800" id="detail-campaign">--</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e4e4e4;">
                                <td class="py-2.5 pr-4 font-medium text-gray-600">Name</td>
                                <td class="py-2.5 text-gray-400">:</td>
                                <td class="py-2.5 text-gray-800" id="detail-name">--</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e4e4e4;">
                                <td class="py-2.5 pr-4 font-medium text-gray-600">Mobile</td>
                                <td class="py-2.5 text-gray-400">:</td>
                                <td class="py-2.5 text-gray-800" id="detail-mobile">--</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e4e4e4;">
                                <td class="py-2.5 pr-4 font-medium text-gray-600">Email</td>
                                <td class="py-2.5 text-gray-400">:</td>
                                <td class="py-2.5 text-gray-800" id="detail-email">--</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e4e4e4;">
                                <td class="py-2.5 pr-4 font-medium text-gray-600">Bill No</td>
                                <td class="py-2.5 text-gray-400">:</td>
                                <td class="py-2.5 text-gray-800" id="detail-bill-no">--</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e4e4e4;">
                                <td class="py-2.5 pr-4 font-medium text-gray-600">Branch</td>
                                <td class="py-2.5 text-gray-400">:</td>
                                <td class="py-2.5 text-gray-800" id="detail-branch">--</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e4e4e4;">
                                <td class="py-2.5 pr-4 font-medium text-gray-600">Offer</td>
                                <td class="py-2.5 text-gray-400">:</td>
                                <td class="py-2.5 font-semibold" id="detail-offer" style="color:#2563eb;">--</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Redeemed On info -->
                    <div id="redeemed-on-info" class="hidden mt-4 text-xs text-gray-500">
                        Redeemed on: <span id="detail-redeemed-on" class="font-medium text-gray-700"></span>
                    </div>

                    <!-- Redeem Button -->
                    <div class="mt-6">
                        <button id="btn-redeem"
                            class="px-5 py-2 rounded-md text-sm font-medium transition-colors"
                            style="background:#18181b;color:#fff;">
                            Redeem Now
                        </button>
                    </div>
                </div>

                <!-- Gift Image -->
                <div id="gift-image-wrap" class="hidden flex-shrink-0">
                    <img id="gift-image" src="" alt="Gift"
                        class="rounded-lg object-contain"
                        style="width:220px;height:220px;border:1px solid #e5e7eb;">
                </div>

            </div>
        </div>
    </div>

</div>

<!-- Redeem Confirmation Modal -->
<div id="redeemConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center" style="background:#dbeafe;">
                    <svg class="w-6 h-6" style="color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Confirm Redeem</h3>
                    <p class="mt-1 text-sm text-gray-500">Are you sure you want to redeem this scratch? This action cannot be undone.</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelRedeem"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                Cancel
            </button>
            <button id="confirmRedeem"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium text-white rounded-md transition-colors"
                style="background:#18181b;">
                Redeem Now
            </button>
        </div>
    </div>
</div>

<!-- Error/Info Modal -->
<div id="infoModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div id="infoModalIcon" class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center" style="background:#fee2e2;">
                    <svg class="w-6 h-6" style="color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 id="infoModalTitle" class="text-base font-semibold text-gray-900">Error</h3>
                    <p id="infoModalMessage" class="mt-1 text-sm text-gray-500"></p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 flex justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="closeInfoModal"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                OK
            </button>
        </div>
    </div>
</div>

<!-- Mobile responsive overrides -->
<style>
@media (max-width: 640px) {
    #search-row {
        flex-direction: column;
        align-items: stretch;
    }
    #search-row #search-unique-id {
        min-width: unset;
        width: 100%;
    }
    #search-row #btn-search,
    #search-row #btn-clear-search {
        width: 100%;
        justify-content: center;
    }
    #details-inner {
        flex-direction: column;
    }
    #gift-image-wrap {
        width: 100%;
        display: flex !important;
        justify-content: center;
    }
    #gift-image {
        width: 100%;
        max-width: 280px;
        height: auto;
    }
}
</style>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

<script>
$(document).ready(function () {

    var currentCustomerId = null;

    // Allow Enter key in search input
    $('#search-unique-id').on('keypress', function (e) {
        if (e.which === 13) { doSearch(); }
    });

    $('#btn-search').on('click', function () {
        doSearch();
    });

    $('#btn-clear-search').on('click', function () {
        $('#search-unique-id').val('');
        hideDetails();
        $('#search-error').addClass('hidden').text('');
    });

    function doSearch() {
        var uniqueId = $('#search-unique-id').val().trim();
        if (!uniqueId) {
            showError('Please enter a Unique ID.');
            return;
        }

        $('#search-error').addClass('hidden');
        $('#btn-search').prop('disabled', true).text('Searching...');

        $.ajax({
            url: "{{ route('user.redeem.search') }}",
            type: 'GET',
            data: { unique_id: uniqueId },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.success) {
                    populateDetails(res.customer);
                } else {
                    showError(res.message);
                    hideDetails();
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'No record found for this Unique ID.';
                showError(msg);
                hideDetails();
            },
            complete: function () {
                $('#btn-search').prop('disabled', false).text('Search');
                // Restore icon
                $('#btn-search').html('<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/></svg> Search');
            }
        });
    }

    function populateDetails(c) {
        currentCustomerId = c.id;

        $('#detail-campaign').text(c.campaign);
        $('#detail-name').text(c.name);
        $('#detail-mobile').text(c.mobile);
        $('#detail-email').text(c.email);
        $('#detail-bill-no').text(c.bill_no);
        $('#detail-branch').text(c.branch_id);
        $('#detail-offer').text(c.offer_text);

        // Gift image
        if (c.gift_image) {
            $('#gift-image').attr('src', c.gift_image);
            $('#gift-image-wrap').removeClass('hidden');
        } else {
            $('#gift-image-wrap').addClass('hidden');
        }

        // Redeemed state
        if (c.redeem == 1) {
            $('#redeemed-badge').removeClass('hidden');
            $('#redeemed-on-info').removeClass('hidden');
            $('#detail-redeemed-on').text(c.redeemed_on ?? '');
            $('#btn-redeem').prop('disabled', true)
                .text('Already Redeemed')
                .css({ 'background': '#d1d5db', 'color': '#6b7280', 'cursor': 'not-allowed' });
        } else {
            $('#redeemed-badge').addClass('hidden');
            $('#redeemed-on-info').addClass('hidden');
            $('#btn-redeem').prop('disabled', false)
                .text('Redeem Now')
                .css({ 'background': '#18181b', 'color': '#fff', 'cursor': 'pointer' });
        }

        $('#customer-details-card').removeClass('hidden');
    }

    function hideDetails() {
        currentCustomerId = null;
        $('#customer-details-card').addClass('hidden');
    }

    function showError(msg) {
        $('#search-error').text(msg).removeClass('hidden');
    }

    function showInfoModal(title, message, type) {
        type = type || 'error';
        $('#infoModalTitle').text(title);
        $('#infoModalMessage').text(message);

        if (type === 'success') {
            $('#infoModalIcon').css('background', '#dcfce7').html(
                '<svg class="w-6 h-6" style="color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
            );
        } else {
            $('#infoModalIcon').css('background', '#fee2e2').html(
                '<svg class="w-6 h-6" style="color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
            );
        }

        $('#infoModal').removeClass('hidden');
    }

    // Close info modal
    $('#closeInfoModal').on('click', function () {
        $('#infoModal').addClass('hidden');
    });

    // Redeem Now button — open confirm modal
    $('#btn-redeem').on('click', function () {
        if (!currentCustomerId) return;
        $('#redeemConfirmModal').removeClass('hidden');
    });

    // Cancel redeem
    $('#cancelRedeem').on('click', function () {
        $('#redeemConfirmModal').addClass('hidden');
    });

    // Confirm redeem
    $('#confirmRedeem').on('click', function () {
        $('#redeemConfirmModal').addClass('hidden');
        $('#btn-redeem').prop('disabled', true).text('Processing...');

        $.ajax({
            url: "{{ route('user.redeem.now') }}",
            type: 'POST',
            data: {
                customer_id: currentCustomerId,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (res) {
                if (res.success) {
                    $('#redeemed-badge').removeClass('hidden');
                    $('#redeemed-on-info').removeClass('hidden');
                    $('#detail-redeemed-on').text(res.redeemed_on);
                    $('#btn-redeem').prop('disabled', true)
                        .text('Already Redeemed')
                        .css({ 'background': '#d1d5db', 'color': '#6b7280', 'cursor': 'not-allowed' });
                    showInfoModal('Success', 'Successfully Redeemed. Thank You.!', 'success');
                } else {
                    showInfoModal('Failed', res.message);
                    $('#btn-redeem').prop('disabled', false).text('Redeem Now');
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'An error occurred. Please try again.';
                showInfoModal('Error', msg);
                $('#btn-redeem').prop('disabled', false).text('Redeem Now');
            }
        });
    });

});
</script>
@endsection
