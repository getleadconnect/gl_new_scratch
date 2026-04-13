@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-1 text-sm text-muted-foreground">Purchase scratch credits for your child users</p>
    </div>

    <!-- Purchase Card -->
    <div class="bg-white border border-border rounded-lg shadow-sm" style="max-width: 560px; margin: 0 auto;">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900" id="purchaseTitle">Purchase Scratch Credits</h3>
        </div>
        <div class="p-6">
            <!-- Step Indicator -->
            <div class="pur-step-indicator">
                <div>
                    <div class="pur-step-dot active" id="purDot1">1</div>
                    <div class="pur-step-label">Select User</div>
                </div>
                <div class="pur-step-line" id="purLine1"></div>
                <div>
                    <div class="pur-step-dot" id="purDot2">2</div>
                    <div class="pur-step-label">Select Plan</div>
                </div>
                <div class="pur-step-line" id="purLine2"></div>
                <div>
                    <div class="pur-step-dot" id="purDot3">3</div>
                    <div class="pur-step-label">Payment</div>
                </div>
            </div>

            <div class="hidden mb-4" id="purchaseAlert"></div>

            <!-- Step 1: Select User -->
            <div id="step1">
                <p class="text-sm text-gray-500 mb-3 text-center">Select a child user</p>
                <div style="max-height:320px;overflow-y:auto;border:1px solid #e5e7eb;border-radius:8px;" class="mb-4">
                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                            <tr>
                                <th style="background:#f3f4f6;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e5e7eb;">#</th>
                                <th style="background:#f3f4f6;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e5e7eb;">Name</th>
                                <th style="background:#f3f4f6;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e5e7eb;">Mobile</th>
                                <th style="background:#f3f4f6;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e5e7eb;">Balance</th>
                                <th style="background:#f3f4f6;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e5e7eb;">Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($childUsers && count($childUsers) > 0)
                                @foreach($childUsers as $index => $cu)
                                <tr id="userRow_{{ $cu->id }}" style="border-bottom:1px solid #f3f4f6;transition:background .15s;">
                                    <td style="padding:7px 10px;color:#374151;">{{ $index + 1 }}</td>
                                    <td style="padding:7px 10px;color:#374151;">{{ $cu->name }}</td>
                                    <td style="padding:7px 10px;color:#374151;">{{ $cu->country_code }} {{ $cu->mobile }}</td>
                                    <td style="padding:7px 10px;">
                                        @php $bal = (int)($cu->balance_count ?? 0); @endphp
                                        <span style="color:{{ $bal > 0 ? '#22c55e' : '#ef4444' }};font-weight:600;">{{ number_format($bal) }}</span>
                                    </td>
                                    <td style="padding:7px 10px;">
                                        <button type="button" class="user-select-btn" id="userBtn_{{ $cu->id }}"
                                            onclick="selectUser({{ $cu->id }}, '{{ addslashes($cu->name) }}')"
                                            style="padding:4px 14px;font-size:12px;font-weight:600;border-radius:5px;cursor:pointer;border:1.5px solid #2563eb;color:#2563eb;background:#fff;transition:all .15s;">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="5" style="padding:20px;text-align:center;color:#9ca3af;">No child users found.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="selected_user_id" value="">
                <input type="hidden" id="selected_user_name" value="">

                <div id="userSummary" class="border rounded-lg p-3 mb-4 hidden" style="background:#f9fafb;border-color:#e5e7eb;">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Selected User</span>
                        <span id="displayUserName" class="font-semibold">—</span>
                    </div>
                </div>

                <button onclick="goToStep2()" class="w-full py-2 px-4 rounded-md text-sm font-medium text-white" style="background:#18181b;">
                    Next - Select Package
                </button>
            </div>

            <!-- Step 2: Select Package -->
            <div id="step2" class="hidden">
                <p class="text-sm text-gray-500 mb-3 text-center">Select a scratch package for <strong id="step2UserName"></strong></p>
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
                                <tr id="pkgRow_{{ $pkg->scratch_count }}" style="border-bottom:1px solid #f3f4f6;transition:background .15s;">
                                    <td style="padding:7px 10px;color:#374151;">{{ $index + 1 }}</td>
                                    <td style="padding:7px 10px;color:#374151;">{{ number_format($pkg->scratch_count) }}</td>
                                    <td style="padding:7px 10px;color:#374151;">₹{{ number_format($pkg->rate, 2) }}</td>
                                    <td style="padding:7px 10px;color:#374151;">₹{{ number_format($pkg->total_amount, 2) }}</td>
                                    <td style="padding:7px 10px;">
                                        <button type="button" class="pkg-select-btn" id="pkgBtn_{{ $pkg->scratch_count }}"
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

                <div id="pkgSummary" class="border rounded-lg p-4 mb-4 hidden" style="background:#f9fafb;border-color:#e5e7eb;">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Scratch Count</span>
                        <span id="purDisplayCount" class="font-semibold">—</span>
                    </div>
                    <div class="flex justify-between items-center text-sm mt-2">
                        <span class="text-gray-500">Rate per scratch</span>
                        <span id="purDisplayRate" class="font-semibold">—</span>
                    </div>
                    <div class="border-t mt-3 pt-3 flex justify-between items-center" style="border-color:#e5e7eb;">
                        <span class="font-bold text-gray-900">Total Amount</span>
                        <span id="purDisplayTotal" class="text-xl font-bold" style="color:#2563eb;">—</span>
                    </div>
                </div>

                <button onclick="goToStep3()" class="w-full py-2 px-4 rounded-md text-sm font-medium text-white" style="background:#18181b;">
                    Proceed to Payment
                </button>
                <button onclick="backToStep1()" class="w-full mt-2 py-2 px-4 border rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" style="border-color:#e5e7eb;">
                    Back
                </button>
            </div>

            <!-- Step 3: Payment -->
            <div id="step3" class="hidden">
                <div id="paySpinner" class="flex flex-col items-center gap-3 py-8">
                    <div style="width:40px;height:40px;border:4px solid #e5e7eb;border-top-color:#2563eb;border-radius:50%;animation:purSpin .8s linear infinite;"></div>
                    <p class="text-sm text-gray-500">Initialising payment...</p>
                </div>
                <div id="payDetails" class="hidden">
                    <div class="border rounded-lg p-4 mb-4 text-center" style="background:#f9fafb;border-color:#e5e7eb;">
                        <p class="text-sm text-gray-500 mb-1">Amount to Pay</p>
                        <p class="text-3xl font-bold" style="color:#2563eb;" id="payAmount">—</p>
                        <p class="text-xs text-gray-500 mt-1" id="payScratch">—</p>
                        <p class="text-xs text-gray-500 mt-1">for <strong id="payUserName">—</strong></p>
                    </div>
                    <button onclick="openRazorpay()" class="w-full py-2 px-4 rounded-md text-sm font-medium text-white" style="background:#18181b;">Pay Now</button>
                    <button onclick="backToStep2()" class="w-full mt-2 py-2 px-4 border rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" style="border-color:#e5e7eb;">Change Plan</button>
                </div>
                <div id="paySuccess" class="hidden text-center py-6">
                    <div style="font-size:48px;">&#9989;</div>
                    <h3 class="text-lg font-bold text-gray-900 mt-3">Payment Successful!</h3>
                    <p class="text-sm text-gray-500 mt-1">Credits added. Refreshing...</p>
                </div>
                <div id="payFailed" class="hidden text-center py-6">
                    <div style="font-size:48px;">&#10060;</div>
                    <h3 class="text-lg font-bold text-gray-900 mt-3">Payment Failed</h3>
                    <p class="text-sm text-gray-500 mt-1" id="payFailedMsg">Something went wrong.</p>
                    <button onclick="backToStep2()" class="mt-4 py-2 px-6 border rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" style="border-color:#e5e7eb;">Try Again</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes purSpin { to { transform: rotate(360deg); } }
.user-select-btn:hover, .user-select-btn.active, .pkg-select-btn:hover, .pkg-select-btn.active { background: #2563eb !important; color: #fff !important; }
.pur-step-indicator { display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; }
.pur-step-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; background: #e5e7eb; color: #6b7280; transition: all .3s; }
.pur-step-dot.active { background: #18181b; color: #fff; }
.pur-step-dot.done { background: #16a34a; color: #fff; }
.pur-step-line { flex: 1; height: 2px; background: #e5e7eb; max-width: 60px; margin: 0 4px; transition: background .3s; }
.pur-step-line.done { background: #16a34a; }
.pur-step-label { font-size: 11px; color: #6b7280; text-align: center; margin-top: 4px; }
</style>

<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script>
var orderData = null;

function updateIndicator(current) {
    for (var i = 1; i <= 3; i++) {
        var dot = document.getElementById('purDot' + i);
        if (i < current) dot.className = 'pur-step-dot done';
        else if (i === current) dot.className = 'pur-step-dot active';
        else dot.className = 'pur-step-dot';
    }
    document.getElementById('purLine1').className = 'pur-step-line' + (current > 1 ? ' done' : '');
    document.getElementById('purLine2').className = 'pur-step-line' + (current > 2 ? ' done' : '');
}

function showAlert(msg, type) {
    var bg = type === 'error' ? 'background:#fef2f2;border:1px solid #fecaca;color:#991b1b;' : 'background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;';
    $('#purchaseAlert').html('<div style="' + bg + 'border-radius:8px;padding:12px 16px;font-size:13px;font-weight:500;">' + msg + '</div>').removeClass('hidden');
}

// Step 1: Select User
function selectUser(id, name) {
    $('.user-select-btn').each(function() { $(this).text('Select').removeClass('active'); });
    $('[id^="userRow_"]').css('background', '');
    $('#userBtn_' + id).text('Selected').addClass('active');
    $('#userRow_' + id).css('background', '#eff6ff');
    $('#selected_user_id').val(id);
    $('#selected_user_name').val(name);
    $('#userSummary').removeClass('hidden');
    $('#displayUserName').text(name);
    $('#purchaseAlert').addClass('hidden');
}

function goToStep2() {
    if (!$('#selected_user_id').val()) { showAlert('Please select a user.', 'error'); return; }
    $('#step1').addClass('hidden');
    $('#step2').removeClass('hidden');
    $('#step2UserName').text($('#selected_user_name').val());
    $('#purchaseTitle').text('Select Package');
    $('#purchaseAlert').addClass('hidden');
    updateIndicator(2);
}

function backToStep1() {
    $('#step2').addClass('hidden');
    $('#step1').removeClass('hidden');
    $('#purchaseTitle').text('Purchase Scratch Credits');
    $('#purchaseAlert').addClass('hidden');
    updateIndicator(1);
    // Reset package selection
    $('.pkg-select-btn').each(function() { $(this).text('Select').removeClass('active'); });
    $('[id^="pkgRow_"]').css('background', '');
    $('#pur_scratch_count, #pur_amount, #pur_rate').val('');
    $('#pkgSummary').addClass('hidden');
}

// Step 2: Select Package
function selectPackage(count, amount, rate) {
    $('.pkg-select-btn').each(function() { $(this).text('Select').removeClass('active'); });
    $('[id^="pkgRow_"]').css('background', '');
    $('#pkgBtn_' + count).text('Selected').addClass('active');
    $('#pkgRow_' + count).css('background', '#eff6ff');
    $('#pur_scratch_count').val(count);
    $('#pur_amount').val(amount);
    $('#pur_rate').val(rate);
    $('#pkgSummary').removeClass('hidden');
    $('#purDisplayCount').text(parseInt(count).toLocaleString('en-IN'));
    $('#purDisplayRate').text('\u20B9' + parseFloat(rate).toFixed(2));
    $('#purDisplayTotal').text('\u20B9' + parseFloat(amount).toLocaleString('en-IN', {minimumFractionDigits: 2}));
    $('#purchaseAlert').addClass('hidden');
}

function goToStep3() {
    if (!$('#pur_scratch_count').val()) { showAlert('Please select a scratch package.', 'error'); return; }

    $('#step2').addClass('hidden');
    $('#step3').removeClass('hidden');
    $('#paySpinner').show();
    $('#payDetails, #paySuccess, #payFailed').addClass('hidden');
    $('#purchaseTitle').text('Payment');
    $('#purchaseAlert').addClass('hidden');
    updateIndicator(3);

    $.ajax({
        url: '{{ route("admin.child-users.purchase.create-order") }}',
        type: 'POST',
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        data: JSON.stringify({
            scratch_count: parseInt($('#pur_scratch_count').val()),
            user_id: parseInt($('#selected_user_id').val())
        }),
        success: function(data) {
            $('#paySpinner').hide();
            if (!data.success) { showAlert(data.message || 'Failed to create order.', 'error'); backToStep2(); return; }
            orderData = data;
            $('#payAmount').text('\u20B9' + parseFloat(data.amount_display).toLocaleString('en-IN', {minimumFractionDigits: 2}));
            $('#payScratch').text(parseInt($('#pur_scratch_count').val()).toLocaleString('en-IN') + ' scratches');
            $('#payUserName').text($('#selected_user_name').val());
            $('#payDetails').removeClass('hidden');
        },
        error: function() { $('#paySpinner').hide(); showAlert('Network error.', 'error'); backToStep2(); }
    });
}

function backToStep2() {
    $('#step3').addClass('hidden');
    $('#step2').removeClass('hidden');
    $('#purchaseTitle').text('Select Package');
    $('#purchaseAlert').addClass('hidden');
    updateIndicator(2);
}

function openRazorpay() {
    if (!orderData) return;
    var scratchCount = $('#pur_scratch_count').val();
    var adminUser = @json(auth()->user());

    var options = {
        key: orderData.razorpay_key,
        amount: orderData.amount,
        currency: orderData.currency,
        order_id: orderData.order_id,
        name: 'GL-SCRATCH',
        description: 'Scratch Credits Purchase',
        prefill: { name: adminUser.name, email: adminUser.email, contact: adminUser.country_code + adminUser.mobile },
        theme: { color: '#2563eb' },
        handler: function(response) { verifyPayment(response, scratchCount); },
        modal: { ondismiss: function() { showAlert('Payment cancelled.', 'error'); } }
    };
    var rzp = new Razorpay(options);
    rzp.open();
}

function verifyPayment(paymentResponse, scratchCount) {
    $('#payDetails').addClass('hidden');
    $('#paySpinner').show();

    $.ajax({
        url: '{{ route("admin.child-users.purchase.verify-payment") }}',
        type: 'POST',
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        data: JSON.stringify({
            razorpay_order_id: paymentResponse.razorpay_order_id,
            razorpay_payment_id: paymentResponse.razorpay_payment_id,
            razorpay_signature: paymentResponse.razorpay_signature,
            scratch_count: parseInt(scratchCount),
            user_id: parseInt($('#selected_user_id').val())
        }),
        success: function(data) {
            $('#paySpinner').hide();
            if (data.success) {
                $('#paySuccess').removeClass('hidden');
                setTimeout(function() { window.location.reload(); }, 1800);
            } else {
                $('#payFailedMsg').text(data.message || 'Verification failed.');
                $('#payFailed').removeClass('hidden');
            }
        },
        error: function() {
            $('#paySpinner').hide();
            $('#payFailedMsg').text('Network error during verification.');
            $('#payFailed').removeClass('hidden');
        }
    });
}
</script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endsection
