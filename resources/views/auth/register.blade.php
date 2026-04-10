@extends('layouts.guest')

<!-- Country Code Picker CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/intlTelInput.css') }}">

<style>
    /* intlTelInput */
    .iti { width: 100%; display: block; }
    .iti__flag-container { padding: 0; }
    .iti__selected-flag { padding: 0 8px; border-right: 1px solid #d1d5db; }
    #regMobileNumber { padding-left: 52px; }
    .iti__country-list { border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0,0,0,.1); max-height: 200px; }
    .iti__country:hover { background-color: #f3f4f6; }
    .iti__country.iti__highlight { background-color: #3b82f6; }

    /* Wizard steps indicator */
    .step-indicator { display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; }
    .step-dot {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700;
        background: #e5e7eb; color: #6b7280;
        transition: all .3s;
    }
    .step-dot.active { background: var(--color-primary, #2563eb); color: #fff; }
    .step-dot.done   { background: #16a34a; color: #fff; }
    .step-line { flex: 1; height: 2px; background: #e5e7eb; max-width: 60px; margin: 0 4px; transition: background .3s; }
    .step-line.done { background: #16a34a; }
    .step-label { font-size: 11px; color: #6b7280; text-align: center; margin-top: 4px; }

    /* Step panels */
    .wizard-step { display: none; }
    .wizard-step.active { display: block; }

    /* Scratch plan table */
    .plan-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .plan-table thead th {
        background: #f3f4f6; padding: 8px 10px; text-align: left;
        font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;
    }
    .plan-table tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .15s; }
    .plan-table tbody tr:hover { background: #f9fafb; }
    .plan-table tbody td { padding: 7px 10px; color: #374151; }
    .plan-table tbody tr.selected-row { background: #eff6ff; }
    .plan-table tbody tr.selected-row td { color: #1d4ed8; font-weight: 600; }
    .select-btn {
        padding: 4px 14px; font-size: 12px; font-weight: 600; border-radius: 5px; cursor: pointer;
        border: 1.5px solid #2563eb; color: #2563eb; background: #fff; transition: all .15s;
    }
    .select-btn:hover, .select-btn.active { background: #2563eb; color: #fff; }
    .plan-scroll { max-height: 280px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; }

    /* Payment step */
    #paymentStep { text-align: center; }
    .payment-spinner {
        display: flex; flex-direction: column; align-items: center; gap: 12px;
        padding: 32px 0;
    }
    .spinner {
        width: 40px; height: 40px; border: 4px solid #e5e7eb;
        border-top-color: var(--color-primary, #2563eb);
        border-radius: 50%; animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Error alert */
    .wizard-alert {
        padding: 10px 14px; border-radius: 6px;
        font-size: 13px; margin-bottom: 12px; display: none;
    }
    .wizard-alert.error   { background: #fee2e2; color: #991b1b; }
    .wizard-alert.success { background: #dcfce7; color: #166534; }
</style>

@section('content')
<div>
    <h2 class="text-2xl font-bold text-center text-foreground mb-4">Create your account</h2>

    <!-- Step Indicator -->
    <div class="step-indicator" id="stepIndicator">
        <div>
            <div class="step-dot active" id="dot1">1</div>
            <div class="step-label">Details</div>
        </div>
        <div class="step-line" id="line1"></div>
        <div>
            <div class="step-dot" id="dot2">2</div>
            <div class="step-label">Plan</div>
        </div>
        <div class="step-line" id="line2"></div>
        <div>
            <div class="step-dot" id="dot3">3</div>
            <div class="step-label">Payment</div>
        </div>
    </div>

    <!-- Alert -->
    <div class="wizard-alert" id="wizardAlert"></div>

    <!-- ══════════════ STEP 1 — User Details ══════════════ -->
    <div class="wizard-step active" id="step1">
        <div class="space-y-4">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-foreground mb-1">Full Name</label>
                <input id="s1_name" type="text" placeholder="John Doe"
                    class="block w-full px-3 py-2 border border-border rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm">
                <p class="text-xs text-red-600 mt-1 hidden" id="err_name"></p>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-foreground mb-1">Email address</label>
                <input id="s1_email" type="email" placeholder="you@example.com"
                    class="block w-full px-3 py-2 border border-border rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm">
                <p class="text-xs text-red-600 mt-1 hidden" id="err_email"></p>
            </div>

            <!-- Mobile with country code -->
            <div>
                <label class="block text-sm font-medium text-foreground mb-1">Mobile Number</label>
                <input id="regMobileNumber" type="tel" placeholder="Enter mobile number"
                    class="block w-full px-3 py-2 border border-border rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm">
                <p class="text-xs text-red-600 mt-1 hidden" id="err_mobile"></p>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-foreground mb-1">Password</label>
                <input id="s1_password" type="password" placeholder="Min 8 characters"
                    class="block w-full px-3 py-2 border border-border rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm">
                <p class="text-xs text-red-600 mt-1 hidden" id="err_password"></p>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-foreground mb-1">Confirm Password</label>
                <input id="s1_password_confirmation" type="password" placeholder="Re-enter password"
                    class="block w-full px-3 py-2 border border-border rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm">
                <p class="text-xs text-red-600 mt-1 hidden" id="err_confirm"></p>
            </div>

            <button onclick="goStep2()"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors mt-2">
                Next →
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-sm text-muted-foreground">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary/90">Sign in</a>
            </p>
        </div>
    </div>

    <!-- ══════════════ STEP 2 — Select Plan ══════════════ -->
    <div class="wizard-step" id="step2">
        <p class="text-sm text-muted-foreground mb-1 text-center">
            Select scratch count : <br> Upto 50000  -> &nbsp;•&nbsp; Rate: <strong>₹1.50</strong> per scratch
        </p>
        <p class="text-sm text-muted-foreground mb-3 text-center">
            50001 to 100000 -> &nbsp;•&nbsp; Rate: <strong>₹1.25</strong> per scratch
        </p>

        <!-- Plan Table -->
        <div class="plan-scroll mb-4">
            <table class="plan-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Scratch Count</th>
                        <th>Amount (₹)</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody id="planTableBody">

                    @if($scratchPackage)
                        @foreach($scratchPackage as $row)
                        <tr id="planRow_{{ $row->scratch_count }}">
                            <td>{{ $row->id }}</td>
                            <td>{{ number_format($row->scratch_count) }}</td>
                            <td>₹{{ number_format($row->total_amount, 2) }}</td>
                            <td>
                                <button type="button" class="select-btn {{ $row->scratch_count === 10000 ? 'active' : '' }}"
                                    id="btn_{{ $row->scratch_count }}" onclick="selectPlan({{ $row->scratch_count }},{{$row->total_amount}},{{$row->rate}})">
                                    {{ $row->scratch_count === 10000 ? 'Selected' : 'Select' }}
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>

        <!-- Hidden radio to hold selected value -->
        <input type="hidden" id="scratch_count_selected" value="10000">
        <input type="hidden" id="scratch_count_amount" value="15000">
        <input type="hidden" id="scratch_count_rate" value="1.50">

        <!-- Selected summary -->
        <div class="border border-border rounded-lg p-4 bg-muted/30 mb-4">
            <div class="flex justify-between items-center text-sm">
                <span class="text-muted-foreground">Scratch Count</span>
                <span id="displayCount" class="font-semibold">10,000</span>
            </div>
            <div class="flex justify-between items-center text-sm mt-2">
                <span class="text-muted-foreground">Rate per scratch</span>
                <span class="font-semibold" id="ratePerScratch" >₹1.50</span>
            </div>
            <div class="border-t border-border mt-3 pt-3 flex justify-between items-center">
                <span class="font-bold text-foreground">Total Amount</span>
                <span id="displayTotal" class="text-xl font-bold text-primary">₹15,000.00</span>
            </div>
        </div>

        <div class="flex gap-2">
            <button onclick="goStep(1)"
                class="flex-1 py-2 px-4 border border-border rounded-md text-sm font-medium text-foreground hover:bg-muted transition-colors">
                ← Back
            </button>
            <button onclick="goStep3()"
                class="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                Proceed to Payment →
            </button>
        </div>
    </div>

    <!-- ══════════════ STEP 3 — Payment ══════════════ -->
    <div class="wizard-step" id="step3">
        <div class="payment-spinner" id="paymentSpinner">
            <div class="spinner"></div>
            <p class="text-sm text-muted-foreground">Initialising payment...</p>
        </div>

        <div id="paymentDetails" class="hidden">
            <div class="border border-border rounded-lg p-4 bg-muted/30 mb-4 text-center">
                <p class="text-sm text-muted-foreground mb-1">Amount to Pay</p>
                <p class="text-3xl font-bold text-primary" id="payDisplayAmount">—</p>
                <p class="text-xs text-muted-foreground mt-1" id="payDisplayScratch">—</p>
            </div>
            <button onclick="openRazorpay()"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                Pay Now
            </button>
            <button onclick="goStep(2)" class="w-full mt-2 py-2 px-4 border border-border rounded-md text-sm font-medium text-foreground hover:bg-muted transition-colors">
                ← Change Plan
            </button>
        </div>

        <div id="paymentSuccess" class="hidden text-center py-6">
            <div style="font-size:48px;">✅</div>
            <h3 class="text-lg font-bold text-foreground mt-3">Payment Successful!</h3>
            <p class="text-sm text-muted-foreground mt-1">Redirecting to your dashboard...</p>
        </div>

        <div id="paymentFailed" class="hidden text-center py-6">
            <div style="font-size:48px;">❌</div>
            <h3 class="text-lg font-bold text-foreground mt-3">Payment Failed</h3>
            <p class="text-sm text-muted-foreground mt-1" id="failedMsg">Something went wrong.</p>
            <button onclick="goStep(2)" class="mt-4 py-2 px-6 border border-border rounded-md text-sm font-medium text-foreground hover:bg-muted transition-colors">
                Try Again
            </button>
        </div>
    </div>
</div>

<!-- intlTelInput JS -->
<script src="{{ asset('assets/js/intlTelInput.min.js') }}"></script>

<script>
// ── intlTelInput setup ──────────────────────────────────────────────
const regPhoneInput = document.querySelector('#regMobileNumber');
const regIti = window.intlTelInput(regPhoneInput, {
    initialCountry: 'in',
    preferredCountries: ['in', 'ae', 'us', 'gb'],
    separateDialCode: true,
    utilsScript: '{{ asset("assets/js/intlTelInput_utils.js") }}'
});

// ── State ───────────────────────────────────────────────────────────
let razorpayOrderData = null;

// ── Helpers ─────────────────────────────────────────────────────────
function showAlert(msg, type = 'error') {
    const el = document.getElementById('wizardAlert');
    el.textContent = msg;
    el.className = 'wizard-alert ' + type;
    el.style.display = 'block';
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
function hideAlert() {
    document.getElementById('wizardAlert').style.display = 'none';
}
function fieldErr(id, msg) {
    const el = document.getElementById(id);
    if (el) { el.textContent = msg; el.classList.remove('hidden'); }
}
function clearErrs() {
    ['err_name','err_email','err_mobile','err_password','err_confirm'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    });
    hideAlert();
}

function goStep(n) {
    document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
    document.getElementById('step' + n).classList.add('active');
    updateIndicator(n);
    hideAlert();
}

function updateIndicator(current) {
    for (let i = 1; i <= 3; i++) {
        const dot  = document.getElementById('dot' + i);
        if (i < current)       { dot.className = 'step-dot done'; }
        else if (i === current){ dot.className = 'step-dot active'; }
        else                   { dot.className = 'step-dot'; }
    }
    for (let i = 1; i <= 2; i++) {
        document.getElementById('line' + i).className = 'step-line' + (i < current ? ' done' : '');
    }
}

// ── Step 1 → Step 2 ─────────────────────────────────────────────────
function goStep2() {
    clearErrs();
    let valid = true;
    const name  = document.getElementById('s1_name').value.trim();
    const email = document.getElementById('s1_email').value.trim();
    const pass  = document.getElementById('s1_password').value;
    const conf  = document.getElementById('s1_password_confirmation').value;
    const phone = regIti.getNumber();

    if (!name)  { fieldErr('err_name', 'Full name is required.'); valid = false; }
    if (!email) { fieldErr('err_email', 'Email is required.'); valid = false; }
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { fieldErr('err_email', 'Invalid email format.'); valid = false; }
    if (!phone || phone.length < 7) { fieldErr('err_mobile', 'Enter a valid mobile number.'); valid = false; }
    if (!pass)  { fieldErr('err_password', 'Password is required.'); valid = false; }
    else if (pass.length < 8) { fieldErr('err_password', 'Password must be at least 8 characters.'); valid = false; }
    if (pass !== conf) { fieldErr('err_confirm', 'Passwords do not match.'); valid = false; }

    if (valid) {
        updateTotal();
        goStep(2);
    }
}

// ── Plan table selection ─────────────────────────────────────────────
function selectPlan(count, amount,rate) {
    // Reset all rows/buttons
    document.querySelectorAll('.select-btn').forEach(b => {
        b.textContent = 'Select'; b.classList.remove('active');
    });
    document.querySelectorAll('.plan-table tbody tr').forEach(r => r.classList.remove('selected-row'));

    // Activate selected
    document.getElementById('btn_' + count).textContent = 'Selected';
    document.getElementById('btn_' + count).classList.add('active');
    document.getElementById('planRow_' + count).classList.add('selected-row');
    document.getElementById('scratch_count_selected').value = count;
    document.getElementById('scratch_count_amount').value = amount;
    document.getElementById('scratch_count_rate').value = rate;

    updateTotal();
}

// ── Step 2 total display ─────────────────────────────────────────────
function updateTotal() {
    const count = parseInt(document.getElementById('scratch_count_selected').value || 10000);
    const total = parseFloat(document.getElementById('scratch_count_amount').value || 15000).toFixed(2);
    const rate  = parseFloat(document.getElementById('scratch_count_rate').value || 1.50).toFixed(2);

    document.getElementById('displayCount').textContent = count.toLocaleString('en-IN');
    document.getElementById('ratePerScratch').textContent = '₹' + parseFloat(rate).toLocaleString('en-IN', {minimumFractionDigits: 2});
    document.getElementById('displayTotal').textContent = '₹' + parseFloat(total).toLocaleString('en-IN', {minimumFractionDigits: 2});
}

// ── Step 2 → Step 3 (create Razorpay order) ──────────────────────────
function goStep3() {
    clearErrs();
    const scratchCount = document.getElementById('scratch_count_selected')?.value;
    if (!scratchCount || parseInt(scratchCount) <= 0) { showAlert('Please select a scratch count.'); return; }

    const countryData   = regIti.getSelectedCountryData();
    const fullNumber    = regIti.getNumber();
    const countryCode   = '+' + countryData.dialCode;
    const mobileOnly    = fullNumber.replace(countryCode, '').trim();

    goStep(3);
    document.getElementById('paymentSpinner').style.display = 'flex';
    document.getElementById('paymentDetails').classList.add('hidden');
    document.getElementById('paymentSuccess').classList.add('hidden');
    document.getElementById('paymentFailed').classList.add('hidden');

    fetch('{{ route("register.create-order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            name:             document.getElementById('s1_name').value.trim(),
            email:            document.getElementById('s1_email').value.trim(),
            country_code:     countryCode,
            mobile:           mobileOnly,
            password:         document.getElementById('s1_password').value,
            password_confirmation: document.getElementById('s1_password_confirmation').value,
            scratch_count:    parseInt(scratchCount),
        })
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('paymentSpinner').style.display = 'none';
        if (!data.success) {
            showAlert(data.message || 'Failed to create payment order.');
            goStep(2);
            return;
        }
        razorpayOrderData = data;
        const count = parseInt(scratchCount);
        document.getElementById('payDisplayAmount').textContent = '₹' + parseFloat(data.amount_display).toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('payDisplayScratch').textContent = count.toLocaleString('en-IN') + ' scratches';
        document.getElementById('paymentDetails').classList.remove('hidden');
    })
    .catch(() => {
        document.getElementById('paymentSpinner').style.display = 'none';
        showAlert('Network error. Please try again.');
        goStep(2);
    });
}

// ── Open Razorpay checkout ────────────────────────────────────────────
function openRazorpay() {
    if (!razorpayOrderData) return;

    const countryData = regIti.getSelectedCountryData();
    const fullNumber  = regIti.getNumber();
    const countryCode = '+' + countryData.dialCode;
    const mobileOnly  = fullNumber.replace(countryCode, '').trim();

    const options = {
        key:      razorpayOrderData.razorpay_key,
        amount:   razorpayOrderData.amount,
        currency: razorpayOrderData.currency,
        order_id: razorpayOrderData.order_id,
        name:     'GL-SCRATCH',
        description: 'Scratch Count Purchase',
        prefill: {
            name:  document.getElementById('s1_name').value.trim(),
            email: document.getElementById('s1_email').value.trim(),
            contact: fullNumber,
        },
        theme: { color: '#2563eb' },
        handler: function(response) {
            verifyAndRegister(response, countryCode, mobileOnly);
        },
        modal: {
            ondismiss: function() {
                showAlert('Payment cancelled. Please try again.');
            }
        }
    };

    const rzp = new Razorpay(options);
    rzp.open();
}

// ── Verify payment & create user ─────────────────────────────────────
function verifyAndRegister(paymentResponse, countryCode, mobileOnly) {
    document.getElementById('paymentDetails').classList.add('hidden');
    document.getElementById('paymentSpinner').style.display = 'flex';

    const scratchCount = parseInt(document.getElementById('scratch_count_selected').value);

    fetch('{{ route("register.verify-payment") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            razorpay_order_id:   paymentResponse.razorpay_order_id,
            razorpay_payment_id: paymentResponse.razorpay_payment_id,
            razorpay_signature:  paymentResponse.razorpay_signature,
            name:                document.getElementById('s1_name').value.trim(),
            email:               document.getElementById('s1_email').value.trim(),
            country_code:        countryCode,
            mobile:              mobileOnly,
            password:            document.getElementById('s1_password').value,
            scratch_count:       scratchCount,
        })
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('paymentSpinner').style.display = 'none';
        if (data.success) {
            document.getElementById('paymentSuccess').classList.remove('hidden');
            setTimeout(() => { window.location.href = data.redirect; }, 1800);
        } else {
            document.getElementById('failedMsg').textContent = data.message || 'Verification failed.';
            document.getElementById('paymentFailed').classList.remove('hidden');
        }
    })
    .catch(() => {
        document.getElementById('paymentSpinner').style.display = 'none';
        document.getElementById('failedMsg').textContent = 'Network error during verification.';
        document.getElementById('paymentFailed').classList.remove('hidden');
    });
}

// Init total on load
updateTotal();
</script>

<!-- Razorpay Checkout JS -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

@endsection
