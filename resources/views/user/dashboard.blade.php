@extends('layouts.user')

<style>
    @media (max-width: 639px) {
        .no-scratch-banner {
            flex-direction: column !important;
        }
    }
</style>

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-1 text-sm text-muted-foreground">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- No Scratch Balance Banner -->
    @if($balanceCount <= 0)
    <div class="no-scratch-banner bg-amber-50 border border-amber-200 rounded-lg p-4 flex flex-row items-center justify-center gap-4" style="border-color:#f2b0b0;">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-amber-800">No scratch credits available!</p>
                <p class="text-xs text-amber-600">Purchase scratch credits to continue using campaigns.</p>
            </div>
        </div>
        <button onclick="openPurchaseModal()"
            class="flex items-center px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors text-sm font-medium whitespace-nowrap">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
            Purchase Scratches
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">

        <!-- Total Scratch Count -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-border">
            <div class="p-4 sm:p-5">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Total Scratch Credits</p>
                        <p class="text-xl sm:text-2xl font-bold text-foreground">{{ number_format($totalCount) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Used Count -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-border">
            <div class="p-4 sm:p-5">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Used Credits</p>
                        <p class="text-xl sm:text-2xl font-bold text-foreground">{{ number_format($usedCount) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Count -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-border">
            <div class="p-4 sm:p-5">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Balance Credits</p>
                        <p class="text-xl sm:text-2xl font-bold text-foreground">{{ number_format($balanceCount) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-border">
            <div class="p-4 sm:p-5">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center"
                             style="background:{{ $subscriptionActive ? '#dcfce7' : '#fee2e2' }};">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="{{ $subscriptionActive ? '#16a34a' : '#dc2626' }}" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Subscription</p>
                        <p class="text-base sm:text-lg font-bold" style="color:{{ $subscriptionActive ? '#16a34a' : '#dc2626' }};">
                            {{ $subscriptionActive ? 'Active' : 'Inactive' }}
                        </p>
                        @if($subscriptionStart && $subscriptionEnd)
                        <p class="text-xs text-muted-foreground mt-0.5 truncate">{{ $subscriptionStart }} – {{ $subscriptionEnd }}</p>
                        @else
                        <p class="text-xs text-muted-foreground mt-0.5">No period set</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Campaign Bar Chart -->
    <div class="bg-white shadow rounded-lg border border-border">
        <div class="px-4 sm:px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #e4e4e4;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2.5">
                <rect x="3" y="12" width="4" height="9"/><rect x="9.5" y="7" width="4" height="14"/><rect x="16" y="3" width="4" height="18"/>
            </svg>
            <h3 class="text-sm font-bold text-gray-800">Customers per Campaign</h3>
            <span class="ml-3 flex items-center gap-1 text-xs text-gray-600">
                <span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#22c55e;"></span> Win
            </span>
            <span class="flex items-center gap-1 text-xs text-gray-600">
                <span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#f87171;"></span> Loss
            </span>
        </div>
        <div class="p-4 sm:p-5">
            @if(count($chartLabels) > 0)
                <canvas id="campaignChart" style="max-height:300px;"></canvas>
            @else
                <p class="text-sm text-muted-foreground text-center py-8">No campaign data available.</p>
            @endif
        </div>
    </div>

</div>

<!-- ══════════════ Purchase Scratch Modal ══════════════ -->
<div id="purchaseModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" style="background-color:rgba(0,0,0,0.4);">
    <div class="bg-white rounded-lg shadow-lg w-full sm:max-w-md mx-4 my-8 animate-in">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900" id="purchaseModalTitle">Purchase Scratches</h3>
            <button onclick="closePurchaseModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <!-- Step Indicator -->
            <div class="pur-step-indicator" id="purStepIndicator">
                <div>
                    <div class="pur-step-dot active" id="purDot1">1</div>
                    <div class="pur-step-label">Select Plan</div>
                </div>
                <div class="pur-step-line" id="purLine1"></div>
                <div>
                    <div class="pur-step-dot" id="purDot2">2</div>
                    <div class="pur-step-label">Payment</div>
                </div>
            </div>

            <!-- Alert -->
            <div class="hidden mb-4" id="purchaseAlert"></div>

            <!-- ── Step 1: Select Plan ── -->
            <div id="purchaseStep1">
                <p class="text-sm text-muted-foreground mb-3 text-center">Select a scratch package</p>

                <div style="max-height:280px;overflow-y:auto;border:1px solid #e5e7eb;border-radius:8px;" class="mb-4">
                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                            <tr>
                                <th style="background:#f3f4f6;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e5e7eb;">#</th>
                                <th style="background:#f3f4f6;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e5e7eb;">Scratch Count</th>
                                <th style="background:#f3f4f6;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e5e7eb;">Rate (₹)</th>
                                <th style="background:#f3f4f6;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e5e7eb;">Amount (₹)</th>
                                <th style="background:#f3f4f6;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e5e7eb;">Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($scratchPackages && count($scratchPackages) > 0)
                                @foreach($scratchPackages as $index => $pkg)
                                <tr id="purRow_{{ $pkg->scratch_count }}" style="border-bottom:1px solid #f3f4f6;transition:background .15s;">
                                    <td style="padding:7px 10px;color:#374151;">{{ $index + 1 }}</td>
                                    <td style="padding:7px 10px;color:#374151;">{{ number_format($pkg->scratch_count) }}</td>
                                    <td style="padding:7px 10px;color:#374151;">₹{{ number_format($pkg->rate, 2) }}</td>
                                    <td style="padding:7px 10px;color:#374151;">₹{{ number_format($pkg->total_amount, 2) }}</td>
                                    <td style="padding:7px 10px;">
                                        <button type="button" class="pur-select-btn" id="purBtn_{{ $pkg->scratch_count }}"
                                            onclick="selectPackage({{ $pkg->scratch_count }}, {{ $pkg->total_amount }}, {{ $pkg->rate }})"
                                            style="padding:4px 14px;font-size:12px;font-weight:600;border-radius:5px;cursor:pointer;border:1.5px solid #2563eb;color:#2563eb;background:#fff;transition:all .15s;">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="5" style="padding:20px;text-align:center;color:#9ca3af;">No packages available.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <input type="hidden" id="pur_scratch_count" value="">
                <input type="hidden" id="pur_amount" value="">
                <input type="hidden" id="pur_rate" value="">

                <!-- Selected summary -->
                <div id="purSummary" class="border border-border rounded-lg p-4 bg-muted/30 mb-4 hidden">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-muted-foreground">Scratch Count</span>
                        <span id="purDisplayCount" class="font-semibold">—</span>
                    </div>
                    <div class="flex justify-between items-center text-sm mt-2">
                        <span class="text-muted-foreground">Rate per scratch</span>
                        <span id="purDisplayRate" class="font-semibold">—</span>
                    </div>
                    <div class="border-t border-border mt-3 pt-3 flex justify-between items-center">
                        <span class="font-bold text-foreground">Total Amount</span>
                        <span id="purDisplayTotal" class="text-xl font-bold text-primary">—</span>
                    </div>
                </div>

                <button onclick="proceedToPayment()"
                    class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    Proceed to Payment
                </button>
            </div>

            <!-- ── Step 2: Payment ── -->
            <div id="purchaseStep2" class="hidden">
                <div id="purPaySpinner" class="flex flex-col items-center gap-3 py-8">
                    <div style="width:40px;height:40px;border:4px solid #e5e7eb;border-top-color:#2563eb;border-radius:50%;animation:purSpin .8s linear infinite;"></div>
                    <p class="text-sm text-muted-foreground">Initialising payment...</p>
                </div>

                <div id="purPayDetails" class="hidden">
                    <div class="border border-border rounded-lg p-4 bg-muted/30 mb-4 text-center">
                        <p class="text-sm text-muted-foreground mb-1">Amount to Pay</p>
                        <p class="text-3xl font-bold text-primary" id="purPayAmount">—</p>
                        <p class="text-xs text-muted-foreground mt-1" id="purPayScratch">—</p>
                    </div>
                    <button onclick="openPurRazorpay()"
                        class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 transition-colors">
                        Pay Now
                    </button>
                    <button onclick="backToStep1()" class="w-full mt-2 py-2 px-4 border border-border rounded-md text-sm font-medium text-foreground hover:bg-muted transition-colors">
                        Change Plan
                    </button>
                </div>

                <div id="purPaySuccess" class="hidden text-center py-6">
                    <div style="font-size:48px;">&#9989;</div>
                    <h3 class="text-lg font-bold text-foreground mt-3">Payment Successful!</h3>
                    <p class="text-sm text-muted-foreground mt-1">Refreshing dashboard...</p>
                </div>

                <div id="purPayFailed" class="hidden text-center py-6">
                    <div style="font-size:48px;">&#10060;</div>
                    <h3 class="text-lg font-bold text-foreground mt-3">Payment Failed</h3>
                    <p class="text-sm text-muted-foreground mt-1" id="purFailedMsg">Something went wrong.</p>
                    <button onclick="backToStep1()" class="mt-4 py-2 px-6 border border-border rounded-md text-sm font-medium text-foreground hover:bg-muted transition-colors">
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes purSpin { to { transform: rotate(360deg); } }
.pur-select-btn:hover, .pur-select-btn.active { background: #2563eb !important; color: #fff !important; }
#purchaseModal .animate-in { animation: purSlideIn .2s ease-out; }
@keyframes purSlideIn { from { transform: scale(0.95) translateY(-10px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }

/* Step indicator */
.pur-step-indicator { display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; }
.pur-step-dot {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700;
    background: #e5e7eb; color: #6b7280;
    transition: all .3s;
}
.pur-step-dot.active { background: var(--color-primary, #2563eb); color: #fff; }
.pur-step-dot.done   { background: #16a34a; color: #fff; }
.pur-step-line { flex: 1; height: 2px; background: #e5e7eb; max-width: 60px; margin: 0 4px; transition: background .3s; }
.pur-step-line.done { background: #16a34a; }
.pur-step-label { font-size: 11px; color: #6b7280; text-align: center; margin-top: 4px; }
</style>

<script>
var purOrderData = null;

function openPurchaseModal() {
    resetPurchaseModal();
    document.getElementById('purchaseModal').classList.remove('hidden');
}

function closePurchaseModal() {
    document.getElementById('purchaseModal').classList.add('hidden');
    resetPurchaseModal();
}

function resetPurchaseModal() {
    document.getElementById('purchaseStep1').classList.remove('hidden');
    document.getElementById('purchaseStep2').classList.add('hidden');
    document.getElementById('purSummary').classList.add('hidden');
    document.getElementById('purchaseAlert').classList.add('hidden');
    document.getElementById('pur_scratch_count').value = '';
    document.getElementById('pur_amount').value = '';
    document.getElementById('pur_rate').value = '';
    purOrderData = null;
    updatePurIndicator(1);

    // Reset all buttons
    document.querySelectorAll('.pur-select-btn').forEach(function(b) {
        b.textContent = 'Select';
        b.classList.remove('active');
    });
    document.querySelectorAll('[id^="purRow_"]').forEach(function(r) {
        r.style.background = '';
    });
}

function updatePurIndicator(current) {
    for (var i = 1; i <= 2; i++) {
        var dot = document.getElementById('purDot' + i);
        if (i < current)       { dot.className = 'pur-step-dot done'; }
        else if (i === current){ dot.className = 'pur-step-dot active'; }
        else                   { dot.className = 'pur-step-dot'; }
    }
    document.getElementById('purLine1').className = 'pur-step-line' + (current > 1 ? ' done' : '');
}

function showPurAlert(msg, type) {
    var el = document.getElementById('purchaseAlert');
    var icon = type === 'error'
        ? '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
        : '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    var bg = type === 'error' ? 'background:#fef2f2;border:1px solid #fecaca;color:#991b1b;' : 'background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;';
    el.innerHTML = '<div style="' + bg + 'border-radius:8px;padding:12px 16px;display:flex;align-items:center;gap:10px;">' + icon + '<span style="font-size:13px;font-weight:500;">' + msg + '</span></div>';
    el.classList.remove('hidden');
}

function selectPackage(count, amount, rate) {
    // Reset all
    document.querySelectorAll('.pur-select-btn').forEach(function(b) {
        b.textContent = 'Select';
        b.classList.remove('active');
    });
    document.querySelectorAll('[id^="purRow_"]').forEach(function(r) {
        r.style.background = '';
    });

    // Activate selected
    var btn = document.getElementById('purBtn_' + count);
    btn.textContent = 'Selected';
    btn.classList.add('active');
    document.getElementById('purRow_' + count).style.background = '#eff6ff';

    document.getElementById('pur_scratch_count').value = count;
    document.getElementById('pur_amount').value = amount;
    document.getElementById('pur_rate').value = rate;

    // Show summary
    document.getElementById('purSummary').classList.remove('hidden');
    document.getElementById('purDisplayCount').textContent = parseInt(count).toLocaleString('en-IN');
    document.getElementById('purDisplayRate').textContent = '\u20B9' + parseFloat(rate).toFixed(2);
    document.getElementById('purDisplayTotal').textContent = '\u20B9' + parseFloat(amount).toLocaleString('en-IN', {minimumFractionDigits: 2});

    document.getElementById('purchaseAlert').classList.add('hidden');
}

function proceedToPayment() {
    var scratchCount = document.getElementById('pur_scratch_count').value;
    if (!scratchCount) {
        showPurAlert('Please select a scratch package.', 'error');
        return;
    }

    // Switch to step 2
    document.getElementById('purchaseStep1').classList.add('hidden');
    document.getElementById('purchaseStep2').classList.remove('hidden');
    document.getElementById('purPaySpinner').style.display = 'flex';
    document.getElementById('purPayDetails').classList.add('hidden');
    document.getElementById('purPaySuccess').classList.add('hidden');
    document.getElementById('purPayFailed').classList.add('hidden');
    document.getElementById('purchaseModalTitle').textContent = 'Payment';
    updatePurIndicator(2);

    fetch('{{ route("user.purchase.create-order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ scratch_count: parseInt(scratchCount) })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        document.getElementById('purPaySpinner').style.display = 'none';
        if (!data.success) {
            showPurAlert(data.message || 'Failed to create payment order.', 'error');
            backToStep1();
            return;
        }
        purOrderData = data;
        document.getElementById('purPayAmount').textContent = '\u20B9' + parseFloat(data.amount_display).toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('purPayScratch').textContent = parseInt(scratchCount).toLocaleString('en-IN') + ' scratches';
        document.getElementById('purPayDetails').classList.remove('hidden');
    })
    .catch(function() {
        document.getElementById('purPaySpinner').style.display = 'none';
        showPurAlert('Network error. Please try again.', 'error');
        backToStep1();
    });
}

function backToStep1() {
    document.getElementById('purchaseStep2').classList.add('hidden');
    document.getElementById('purchaseStep1').classList.remove('hidden');
    document.getElementById('purchaseModalTitle').textContent = 'Purchase Scratches';
    updatePurIndicator(1);
}

function openPurRazorpay() {
    if (!purOrderData) return;
    var scratchCount = document.getElementById('pur_scratch_count').value;
    var user = @json(auth()->user());

    var options = {
        key:      purOrderData.razorpay_key,
        amount:   purOrderData.amount,
        currency: purOrderData.currency,
        order_id: purOrderData.order_id,
        name:     'GL-SCRATCH',
        description: 'Scratch Count Purchase',
        prefill: {
            name:    user.name,
            email:   user.email,
            contact: user.country_code + user.mobile,
        },
        theme: { color: '#2563eb' },
        handler: function(response) {
            verifyPurPayment(response, scratchCount);
        },
        modal: {
            ondismiss: function() {
                showPurAlert('Payment cancelled. Please try again.', 'error');
            }
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
}

function verifyPurPayment(paymentResponse, scratchCount) {
    document.getElementById('purPayDetails').classList.add('hidden');
    document.getElementById('purPaySpinner').style.display = 'flex';

    fetch('{{ route("user.purchase.verify-payment") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            razorpay_order_id:   paymentResponse.razorpay_order_id,
            razorpay_payment_id: paymentResponse.razorpay_payment_id,
            razorpay_signature:  paymentResponse.razorpay_signature,
            scratch_count:       parseInt(scratchCount),
        })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        document.getElementById('purPaySpinner').style.display = 'none';
        if (data.success) {
            document.getElementById('purPaySuccess').classList.remove('hidden');
            setTimeout(function() { window.location.href = data.redirect; }, 1800);
        } else {
            document.getElementById('purFailedMsg').textContent = data.message || 'Verification failed.';
            document.getElementById('purPayFailed').classList.remove('hidden');
        }
    })
    .catch(function() {
        document.getElementById('purPaySpinner').style.display = 'none';
        document.getElementById('purFailedMsg').textContent = 'Network error during verification.';
        document.getElementById('purPayFailed').classList.remove('hidden');
    });
}

// Close modal on backdrop click
document.getElementById('purchaseModal').addEventListener('click', function(e) {
    if (e.target === this) closePurchaseModal();
});
</script>

<!-- Razorpay Checkout JS -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

@if(count($chartLabels) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    var labels = @json($chartLabels);
    var winData  = @json($chartWin);
    var lossData = @json($chartLoss);

    new Chart(document.getElementById('campaignChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Win',
                    data: winData,
                    backgroundColor: '#22c55e',
                    borderRadius: 4,
                    barPercentage: 0.6,
                },
                {
                    label: 'Loss',
                    data: lossData,
                    backgroundColor: '#f87171',
                    borderRadius: 4,
                    barPercentage: 0.6,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 12 } }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: { size: 12 }
                    },
                    grid: { color: '#f3f4f6' }
                }
            }
        }
    });
})();
</script>
@endif

@endsection
