@extends('layouts.user')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">Manage your scratch web links</p>
        </div>
        <div class="flex items-center gap-2">
            <button id="btn-add-link"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                style="background:#18181b;color:#fff;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Link
            </button>
            <button id="btn-add-multiple"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                style="background:#18181b;color:#fff;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Multiple Links
            </button>
            <button id="btn-qr-pdf"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium border border-input bg-background text-foreground hover:bg-accent transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 14h3v3m0 4h4v-4m-4 0h4"/>
                </svg>
                Qr-Code PDF
            </button>
        </div>
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

                <!-- Campaign -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Campaign</label>
                    <select id="filter-campaign"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary"
                        style="min-width:200px;">
                        <option value="">Select Campaign</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Link Section -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Link Type</label>
                    <select id="filter-link-type"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-primary"
                        style="min-width:160px;">
                        <option value="">--select--</option>
                        <option value="Single">Single</option>
                        <option value="Multiple">Multiple</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button id="btn-filter"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        style="background:#18181b;color:#fff;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4-2A1 1 0 018 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        Filter
                    </button>
                    <button id="btn-clear"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        style="background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            <span class="text-sm font-semibold text-gray-700">Web links</span>
        </div>
        <div class="p-4">
            <table id="scratch-links-table" class="data-table w-full" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Offer Name</th>
                        <th>Link</th>
                        <th>QrCode</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Email<br><span style="font-size:10px;font-weight:400;color:#6b7280;">(Required)</span></th>
                        <th>BillNo<br><span style="font-size:10px;font-weight:400;color:#6b7280;">(Required)</span></th>
                        <th>Shop<br><span style="font-size:10px;font-weight:400;color:#6b7280;">(Required)</span></th>
                        <th>Click Count</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

@php
	$user_id=Auth::user()->id;
	$link=env('SHORT_LINK_DOMAIN').'/'.$user_id.'/';
@endphp

<!-- Add Link Modal -->
<div id="addLinkModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg w-full mx-4" style="max-width:480px;">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #e5e7eb;">
            <h3 class="text-base font-semibold text-gray-900">Add gl-link</h3>
            <button id="closeAddLinkModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 space-y-4">
            <!-- Info bullets -->
            <ul style="padding:18px 10px;list-style:disc;margin:0;">
                <li class="text-sm" style="color:#2563eb;">To create single scratch link for customers to scratch</li>
            </ul>

            <!-- Short Link (read-only, generated) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Short Link <span style="color:#dc2626;">*</span>
                </label>
                <input type="text" id="short_link" readonly value="{{$link}}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                    style="background:#f9fafb;color:#6b7280;"
                    placeholder="Scratch link">
            </div>

            <!-- Short Code -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Enter Short Code
                    <span style="font-size:11px;color:#6b7280;">(Min 5 characters)</span>
                    <span style="color:#dc2626;">*</span>
                </label>
                <input type="text" id="short_code_input" maxlength="50"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                    placeholder="Short Link">
                <p id="short_code_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
            </div>

            <!-- Scratch Your Offer (Campaign) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Select Offer Name <span style="color:#dc2626;">*</span>
                </label>
                <select id="modal_campaign_id"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">select</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                    @endforeach
                </select>
                <p id="campaign_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
            </div>

            <!-- Bill Number Required -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bill Number Required?</label>
                <div class="flex items-center gap-5">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="bill_number_required" value="0" checked
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        NO
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="bill_number_required" value="1"
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        YES
                    </label>
                </div>
            </div>

            <!-- Branch Required -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Branch Required?</label>
                <div class="flex items-center gap-5">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="branch_required" value="0" checked
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        NO
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="branch_required" value="1"
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        YES
                    </label>
                </div>
            </div>

            <!-- Email Required -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Required?</label>
                <div class="flex items-center gap-5 mb-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="email_required" value="0" checked
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        NO
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="email_required" value="1"
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        YES
                    </label>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;border-top:1px solid #e5e7eb;">
            <button id="cancelAddLink"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                style="background:#979696;color:#fff;border:none;cursor:pointer;">
                Close
            </button>
            <button id="saveLink"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                style="background:#2c2d2d;color:#fff;border:none;cursor:pointer;">
                Save Link
            </button>
        </div>
    </div>
</div>

<!-- Edit Link Modal -->
<div id="editLinkModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg w-full mx-4" style="max-width:480px;">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #e5e7eb;">
            <h3 class="text-base font-semibold text-gray-900">Edit gl-link</h3>
            <button id="closeEditLinkModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 space-y-4">
            <input type="hidden" id="edit_link_id">

            <!-- Short Link (read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Short Link</label>
                <input type="text" id="edit_short_link" readonly
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                    style="background:#f9fafb;color:#6b7280;">
            </div>

            <!-- Scratch Offer (Campaign) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Scratch Offer <span style="color:#dc2626;">*</span>
                </label>
                <select id="edit_campaign_id"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">select</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                    @endforeach
                </select>
                <p id="edit_campaign_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
            </div>

            <!-- Bill Number Required -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bill Number Required?</label>
                <div class="flex items-center gap-5">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="edit_bill_number_required" value="0" checked
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        NO
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="edit_bill_number_required" value="1"
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        YES
                    </label>
                </div>
            </div>

            <!-- Branch Required -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Branch Required?</label>
                <div class="flex items-center gap-5">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="edit_branch_required" value="0" checked
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        NO
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="edit_branch_required" value="1"
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        YES
                    </label>
                </div>
            </div>

            <!-- Email Required -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Required?</label>
                <div class="flex items-center gap-5">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="edit_email_required" value="0" checked
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        NO
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="edit_email_required" value="1"
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        YES
                    </label>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div style="border-top:1px solid #e5e7eb;"></div>

        <!-- Footer -->
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelEditLink"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                style="background:#979696;color:#fff;border:none;cursor:pointer;">
                Close
            </button>
            <button id="updateLink"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                style="background:#2563eb;color:#fff;border:none;cursor:pointer;">
                Update Link
            </button>
        </div>
    </div>
</div>

<!-- Generate Multiple Links Modal -->
<div id="multipleLinksModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;font-size:14px;">
    <div class="bg-white rounded-lg shadow-lg w-full mx-4" style="max-width:480px;">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #e5e7eb;">
            <h3 class="text-base font-semibold text-gray-900">Generate multiple links</h3>
            <button id="closeMultipleModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 space-y-4">
            <!-- Info bullets -->
            <ul style="padding:18px 10px;list-style:disc;margin:0;">
                <li class="text-sm" style="color:#2563eb;">In this form, create one-time scratch links only.
                <li class="text-sm" style="color:#2563eb;">Once scratched, the link is automatically disabled.</li>
            </ul>

            <!-- Short Link (read-only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Short Link <span style="color:#dc2626;">*</span>
                </label>
                <input type="text" id="multi_short_link" readonly value="{{ $link }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                    style="background:#f9fafb;color:#6b7280;">
            </div>

            <!-- Scratch Your Offer (Campaign) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Select Offer Name <span style="color:#dc2626;">*</span>
                </label>
                <select id="multi_campaign_id"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">select</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                    @endforeach
                </select>
                <p id="multi_campaign_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
            </div>

            <!-- Shop Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Shop Selection in scratch link?</label>
                <div class="flex items-center gap-5 mb-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="multi_branch_required" value="0" checked
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        NO
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="multi_branch_required" value="1"
                            style="accent-color:#2563eb;width:16px;height:16px;">
                        YES
                    </label>
                </div>
            </div>

            <!-- Links Count -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Links Count
                    <span style="font-size:11px;color:#6b7280;">(Max : 1000 links only)</span>
                    <span style="color:#dc2626;">*</span>
                </label>
                <input type="number" id="multi_link_count" min="1" max="1000"
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                    style="width:180px;"
                    placeholder="link count">
                <p id="multi_count_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
            </div>
        </div>

        <!-- Divider -->
        <div style="border-top:1px solid #e5e7eb;"></div>

        <!-- Footer -->
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelMultipleModal"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                style="background:#979696;color:#fff;border:none;cursor:pointer;">
                Close
            </button>
            <button id="generateLinks"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                style="background:#2c2d2d;color:#fff;border:none;cursor:pointer;">
                Generate Links
            </button>
        </div>
    </div>
</div>

<!-- Status Toggle Confirmation Modal -->
<div id="statusConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center" id="statusModalIconBg">
                    <svg class="w-6 h-6" id="statusModalIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Change Status</h3>
                    <p class="mt-1 text-sm text-gray-500" id="statusConfirmText">Are you sure you want to change the status?</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelStatusChange"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                Cancel
            </button>
            <button id="confirmStatusChange"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium text-white rounded-md"
                style="background:#18181b;">
                Confirm
            </button>
        </div>
    </div>
</div>

<!-- QR Code PDF Modal -->
<div id="qrPdfModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color:#c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg w-full mx-4" style="max-width:480px;">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #e5e7eb;">
            <h3 class="text-base font-semibold text-gray-900">Generate Qr-Code PDF</h3>
            <button id="closeQrPdfModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 space-y-4">
            <!-- Info bullets -->
            <ul style="padding:14px 10px;list-style:disc;margin:0;background:#f0f9ff;border-radius:6px;">
                <li class="text-sm mb-1" style="color:#2563eb;">This is used for generate qr-code pdf file for <strong>Multiple links</strong></li>
                <li class="text-sm mb-1" style="color:#2563eb;">Create multiple links one by one. Click '<strong>Add Multiple Links</strong>' button</li>
                <li class="text-sm" style="color:#2563eb;">Then use this Qr-code generator option.</li>
            </ul>

            <!-- Select Campaign -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Select Campaign <span style="color:#dc2626;">*</span>
                </label>
                <select id="qrpdf_campaign_id"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                    <option value="">select</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                    @endforeach
                </select>
                <p id="qrpdf_campaign_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
            </div>

            <!-- Select Link Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Select link section to pdf <span style="color:#dc2626;">*</span>
                </label>
                <select id="qrpdf_section_id"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                    disabled>
                    <option value="">--select--</option>
                </select>
                <p id="qrpdf_section_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
            </div>
        </div>

        <!-- Divider -->
        <div style="border-top:1px solid #e5e7eb;"></div>

        <!-- Footer -->
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelQrPdfModal"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                style="background:#e53e3e;color:#fff;border:none;cursor:pointer;">
                Close
            </button>
            <button id="downloadQrPdf"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium rounded-md"
                style="background:#2563eb;color:#fff;border:none;cursor:pointer;">
                Download PDF
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background-color: #c9c4c442;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Delete Link</h3>
                    <p class="mt-1 text-sm text-gray-500">Are you sure you want to delete this link? This action cannot be undone.</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 flex gap-3 justify-end rounded-b-lg" style="background:#f9fafb;">
            <button id="cancelDelete"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium border border-input bg-background rounded-md hover:bg-accent transition-colors">
                Cancel
            </button>
            <button id="confirmDelete"
                class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {

    var table = $('#scratch-links-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: false,
        paging: true,
        pageLength: 50,
        pagingType: 'simple_numbers',
        lengthChange: true,
        language: {
            search: '',
            searchPlaceholder: 'Search links...',
        },
        ajax: {
            url: "{{ route('user.scratch-links.data') }}",
            type: 'GET',
            data: function (d) {
                d.campaign_id = $('#filter-campaign').val();
                d.link_type   = $('#filter-link-type').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',  name: 'DT_RowIndex',  orderable: false, searchable: false },
            { data: 'offer_name',   name: 'offer_name',   orderable: false, searchable: false },
            { data: 'link_url',     name: 'link',         orderable: false, searchable: false },
            { data: 'qrcode_col',   name: 'qrcode_col',   orderable: false, searchable: false },
            { data: 'short_code',   name: 'short_code',   searchable: true },
            { data: 'link_type',   name: 'link_type',   searchable: true },
            { data: 'email_req',    name: 'email_req',    orderable: false, searchable: false },
            { data: 'billno_req',   name: 'billno_req',   orderable: false, searchable: false },
            { data: 'shop_req',     name: 'shop_req',     orderable: false, searchable: false },
            { data: 'click_count',  name: 'click_count',  searchable: false },
            { data: 'status_col',   name: 'status_col',   orderable: false, searchable: false },
            { data: 'action',       name: 'action',       orderable: false, searchable: false },
        ],
    });

    // Filter
    $('#btn-filter').on('click', function () {
        table.ajax.reload();
    });

    // Clear
    $('#btn-clear').on('click', function () {
        $('#filter-campaign').val('');
        $('#filter-link-type').val('');
        table.ajax.reload();
    });

    // ─── Add Link Modal ───────────────────────────────────────────
   
    function openAddLinkModal() {
        $('#short_code_input').val('');
        $('#generated_link').val('');
        $('#modal_campaign_id').val('');
        $('input[name="bill_number_required"][value="0"]').prop('checked', true);
        $('input[name="branch_required"][value="0"]').prop('checked', true);
        $('input[name="email_required"][value="0"]').prop('checked', true);
        $('#short_code_error').hide().text('');
        $('#campaign_error').hide().text('');
        $('#addLinkModal').removeClass('hidden');
    }

    function closeAddLinkModal() {
        $('#addLinkModal').addClass('hidden');
    }

    $('#btn-add-link').on('click', function () {
        openAddLinkModal();
    });

    $('#closeAddLinkModal, #cancelAddLink').on('click', function () {
        closeAddLinkModal();
    });


    // Save Link
    $('#saveLink').on('click', function () {
        var shortCode  = $('#short_code_input').val().trim();
        var campaignId = $('#modal_campaign_id').val();
        var valid      = true;

        $('#short_code_error').hide().text('');
        $('#campaign_error').hide().text('');

        if (shortCode.length < 5) {
            $('#short_code_error').text('Short code must be at least 5 characters.').show();
            valid = false;
        }

        if (!campaignId) {
            $('#campaign_error').text('Please select an offer.').show();
            valid = false;
        }

        if (!valid) return;

        var shortLink = $("#short_link").val();
        var billReq   = $('input[name="bill_number_required"]:checked').val();
        var branchReq = $('input[name="branch_required"]:checked').val();
        var emailReq  = $('input[name="email_required"]:checked').val();

        $('#saveLink').prop('disabled', true).text('Saving...');

        $.ajax({
            url: "{{ route('user.scratch-links.store') }}",
            type: 'POST',
            data: {
                _token:                '{{ csrf_token() }}',
                campaign_id:           campaignId,
                short_link:            shortLink,
                short_code:            shortCode,
                bill_number_required:  billReq,
                branch_required:       branchReq,
                email_required:        emailReq,
            },
            success: function (res) {
                $('#saveLink').prop('disabled', false).text('Save Link');
                if (res.success) {
                    closeAddLinkModal();
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                $('#saveLink').prop('disabled', false).text('Save Link');
                var errors = xhr.responseJSON && xhr.responseJSON.errors;
                if (errors) {
                    if (errors.short_code) {
                        $('#short_code_error').text(errors.short_code[0]).show();
                    }
                    if (errors.campaign_id) {
                        $('#campaign_error').text(errors.campaign_id[0]).show();
                    }
                } else {
                    showNotification('error', 'Failed to save link.');
                }
            }
        });
    });

    // ─── Delete Modal ────────────────────────────────────────────
    var deleteLinkId = null;

    window.deleteLink = function (id) {
        deleteLinkId = id;
        $('#deleteConfirmModal').removeClass('hidden');
    };

    $('#cancelDelete').on('click', function () {
        $('#deleteConfirmModal').addClass('hidden');
        deleteLinkId = null;
    });

    $('#confirmDelete').on('click', function () {
        if (!deleteLinkId) return;
        $.ajax({
            url: "{{ url('user/scratch-links') }}/" + deleteLinkId,
            type: 'POST',
            data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
            success: function (res) {
                $('#deleteConfirmModal').addClass('hidden');
                deleteLinkId = null;
                if (res.success) {
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function () {
                $('#deleteConfirmModal').addClass('hidden');
                showNotification('error', 'Failed to delete link.');
            }
        });
    });

    // ─── Generate Multiple Links Modal ───────────────────────────
    function openMultipleLinksModal() {
        $('#multi_campaign_id').val('');
        $('input[name="multi_branch_required"][value="0"]').prop('checked', true);
        $('#multi_link_count').val('');
        $('#multi_campaign_error').hide().text('');
        $('#multi_count_error').hide().text('');
        $('#multipleLinksModal').removeClass('hidden');
    }

    function closeMultipleLinksModal() {
        $('#multipleLinksModal').addClass('hidden');
    }

    $('#btn-add-multiple').on('click', function () {
        openMultipleLinksModal();
    });

    $('#closeMultipleModal, #cancelMultipleModal').on('click', function () {
        closeMultipleLinksModal();
    });

    $('#generateLinks').on('click', function () {
        var campaignId = $('#multi_campaign_id').val();
        var linkCount  = parseInt($('#multi_link_count').val());
        var valid      = true;

        $('#multi_campaign_error').hide().text('');
        $('#multi_count_error').hide().text('');

        if (!campaignId) {
            $('#multi_campaign_error').text('Please select an offer.').show();
            valid = false;
        }

        if (!linkCount || linkCount < 1 || linkCount > 1000) {
            $('#multi_count_error').text('Enter a count between 1 and 1000.').show();
            valid = false;
        }

        if (!valid) return;

        var branchReq = $('input[name="multi_branch_required"]:checked').val();
        var shortLink = $('#multi_short_link').val();

        $('#generateLinks').prop('disabled', true).text('Generating...');

        $.ajax({
            url: "{{ route('user.scratch-links.store-multiple') }}",
            type: 'POST',
            data: {
                _token:           '{{ csrf_token() }}',
                campaign_id:      campaignId,
                short_link:       shortLink,
                branch_required:  branchReq,
                link_count:       linkCount,
            },
            success: function (res) {
                $('#generateLinks').prop('disabled', false).text('Generate Links');
                if (res.success) {
                    closeMultipleLinksModal();
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                $('#generateLinks').prop('disabled', false).text('Generate Links');
                
                showNotification('error', xhr.responseJSON?.message || 'Failed to generate links.');
                /*var errors = xhr.responseJSON && xhr.responseJSON.errors;
                if (errors) {
                    if (errors.campaign_id) $('#multi_campaign_error').text(errors.campaign_id[0]).show();
                    if (errors.link_count)  $('#multi_count_error').text(errors.link_count[0]).show();
                } else {
                    showNotification('error', 'Failed to generate links.');
                }*/
            }
        });
    });

    // ─── Status Toggle Modal ──────────────────────────────────────
    var toggleLinkId   = null;
    var toggleCurrent  = null;

    window.toggleStatus = function (id, currentStatus) {
        toggleLinkId  = id;
        toggleCurrent = currentStatus;

        if (currentStatus == 1) {
            $('#statusConfirmText').text('This link is currently Active. Do you want to set it to Inactive?');
            $('#statusModalIconBg').css('background', '#fef9c3');
            $('#statusModalIcon').css('stroke', '#ca8a04');
        } else {
            $('#statusConfirmText').text('This link is currently Inactive. Do you want to set it to Active?');
            $('#statusModalIconBg').css('background', '#dcfce7');
            $('#statusModalIcon').css('stroke', '#16a34a');
        }

        $('#statusConfirmModal').removeClass('hidden');
    };

    $('#cancelStatusChange').on('click', function () {
        $('#statusConfirmModal').addClass('hidden');
        toggleLinkId  = null;
        toggleCurrent = null;
    });

    $('#confirmStatusChange').on('click', function () {
        if (!toggleLinkId) return;
        $.ajax({
            url: "{{ url('user/scratch-links') }}/" + toggleLinkId + "/toggle",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                $('#statusConfirmModal').addClass('hidden');
                toggleLinkId  = null;
                toggleCurrent = null;
                if (res.success) {
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function () {
                $('#statusConfirmModal').addClass('hidden');
                showNotification('error', 'Failed to change status.');
            }
        });
    });

    // ─── QR Code PDF Modal ────────────────────────────────────────
    function openQrPdfModal() {
        $('#qrpdf_campaign_id').val('');
        $('#qrpdf_section_id').html('<option value="">--select--</option>').prop('disabled', true);
        $('#qrpdf_campaign_error').hide().text('');
        $('#qrpdf_section_error').hide().text('');
        $('#qrPdfModal').removeClass('hidden');
    }

    function closeQrPdfModal() {
        $('#qrPdfModal').addClass('hidden');
    }

    $('#btn-qr-pdf').on('click', function () {
        openQrPdfModal();
    });

    $('#closeQrPdfModal, #cancelQrPdfModal').on('click', function () {
        closeQrPdfModal();
    });

    // Load link sections when campaign changes
    $('#qrpdf_campaign_id').on('change', function () {
        var campaignId = $(this).val();
        var $section   = $('#qrpdf_section_id');

        $section.html('<option value="">--select--</option>').prop('disabled', true);
        $('#qrpdf_section_error').hide().text('');

        if (!campaignId) return;

        $.ajax({
            url: "{{ route('user.scratch-links.link-sections') }}",
            type: 'GET',
            data: { campaign_id: campaignId },
            success: function (res) {
                if (res.success && res.data.length) {
                    $.each(res.data, function (i, s) {
                        $section.append('<option value="' + s.id + '">' + s.section_name + '</option>');
                    });
                    $section.prop('disabled', false);
                } else {
                    $section.html('<option value="">No sections found</option>');
                }
            },
            error: function () {
                $section.html('<option value="">Failed to load</option>');
            }
        });
    });

    // Download PDF
    $('#downloadQrPdf').on('click', function () {
        var campaignId = $('#qrpdf_campaign_id').val();
        var sectionId  = $('#qrpdf_section_id').val();
        var valid      = true;

        $('#qrpdf_campaign_error').hide().text('');
        $('#qrpdf_section_error').hide().text('');

        if (!campaignId) {
            $('#qrpdf_campaign_error').text('Please select a campaign.').show();
            valid = false;
        }
        if (!sectionId) {
            $('#qrpdf_section_error').text('Please select a link section.').show();
            valid = false;
        }
        if (!valid) return;

        var url = "{{ route('user.scratch-links.qr-pdf') }}?section_id=" + sectionId;
        window.location.href = url;
        closeQrPdfModal();
    });

    // ─── Edit Link Modal ─────────────────────────────────────────
    function closeEditLinkModal() {
        $('#editLinkModal').addClass('hidden');
    }

    $('#closeEditLinkModal, #cancelEditLink').on('click', function () {
        closeEditLinkModal();
    });

    window.editLink = function (id) {
        $('#edit_campaign_error').hide().text('');

        $.ajax({
            url: "{{ url('user/scratch-links') }}/" + id + "/edit",
            type: 'GET',
            success: function (res) {
                if (!res.success) {
                    showNotification('error', res.message);
                    return;
                }
                var d = res.data;
                $('#edit_link_id').val(d.id);
                $('#edit_short_link').val(d.link ?? '');
                $('#edit_campaign_id').val(d.campaign_id ?? '');
                $('input[name="edit_bill_number_required"][value="' + (d.bill_number_required ?? 0) + '"]').prop('checked', true);
                $('input[name="edit_branch_required"][value="' + (d.branch_required ?? 0) + '"]').prop('checked', true);
                $('input[name="edit_email_required"][value="' + (d.email_required ?? 0) + '"]').prop('checked', true);
                $('#editLinkModal').removeClass('hidden');
            },
            error: function () {
                showNotification('error', 'Failed to load link data.');
            }
        });
    };

    $('#updateLink').on('click', function () {
        var id         = $('#edit_link_id').val();
        var campaignId = $('#edit_campaign_id').val();

        $('#edit_campaign_error').hide().text('');

        if (!campaignId) {
            $('#edit_campaign_error').text('Please select an offer.').show();
            return;
        }

        $('#updateLink').prop('disabled', true).text('Updating...');

        $.ajax({
            url: "{{ url('user/scratch-links') }}/" + id,
            type: 'POST',
            data: {
                _method:               'PUT',
                _token:                '{{ csrf_token() }}',
                campaign_id:           campaignId,
                bill_number_required:  $('input[name="edit_bill_number_required"]:checked').val(),
                branch_required:       $('input[name="edit_branch_required"]:checked').val(),
                email_required:        $('input[name="edit_email_required"]:checked').val(),
            },
            success: function (res) {
                $('#updateLink').prop('disabled', false).text('Update Link');
                if (res.success) {
                    closeEditLinkModal();
                    table.ajax.reload();
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                $('#updateLink').prop('disabled', false).text('Update Link');
                var errors = xhr.responseJSON && xhr.responseJSON.errors;
                if (errors && errors.campaign_id) {
                    $('#edit_campaign_error').text(errors.campaign_id[0]).show();
                } else {
                    showNotification('error', 'Failed to update link.');
                }
            }
        });
    });

});

function showNotification(type, message) {
    var bg = type === 'success' ? '#16a34a' : (type === 'error' ? '#dc2626' : '#2563eb');
    var el = $('<div style="position:fixed;top:16px;right:16px;z-index:9999;padding:12px 20px;border-radius:8px;color:#fff;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,.15);background:' + bg + ';">' + message + '</div>');
    $('body').append(el);
    setTimeout(function () { el.fadeOut(300, function () { el.remove(); }); }, 3000);
}
</script>

@endsection
