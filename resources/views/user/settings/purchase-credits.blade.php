@extends('layouts.user')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">Purchase scratch credits for your campaigns</p>
        </div>
        <div class="text-right">
            <div class="text-4xl font-bold" style="color:#000;">{{ $balanceCount }}</div>
            <p class="text-sm text-gray-500 mt-1">Current Balance</p>
        </div>
    </div>

    <!-- Purchase Card -->
    <div class="bg-white border border-border rounded-lg shadow-sm" style="max-width: 520px; margin: 0 auto;">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900" id="purchaseModalTitle">Purchase Scratch Credits</h3>
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

            <!-- Step 1: Select Plan -->
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

            <!-- Step 2: Payment -->
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
                    <p class="text-sm text-muted-foreground mt-1">Refreshing page...</p>
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

function resetPurchaseForm() {
    document.getElementById('purchaseStep1').classList.remove('hidden');
    document.getElementById('purchaseStep2').classList.add('hidden');
    document.getElementById('purSummary').classList.add('hidden');
    document.getElementById('purchaseAlert').classList.add('hidden');
    document.getElementById('pur_scratch_count').value = '';
    document.getElementById('pur_amount').value = '';
    document.getElementById('pur_rate').value = '';
    purOrderData = null;
    updatePurIndicator(1);

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
    document.querySelectorAll('.pur-select-btn').forEach(function(b) {
        b.textContent = 'Select';
        b.classList.remove('active');
    });
    document.querySelectorAll('[id^="purRow_"]').forEach(function(r) {
        r.style.background = '';
    });

    var btn = document.getElementById('purBtn_' + count);
    btn.textContent = 'Selected';
    btn.classList.add('active');
    document.getElementById('purRow_' + count).style.background = '#eff6ff';

    document.getElementById('pur_scratch_count').value = count;
    document.getElementById('pur_amount').value = amount;
    document.getElementById('pur_rate').value = rate;

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
            'Accept': 'application/json',
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
            'Accept': 'application/json',
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
            setTimeout(function() { window.location.reload(); }, 1800);
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
</script>

<!-- Razorpay Checkout JS -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endsection
