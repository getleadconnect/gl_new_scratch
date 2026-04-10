@extends('layouts.user')

@section('content')
<div class="space-y-4">

    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-1 text-sm text-muted-foreground">Manage your application settings</p>
    </div>

    <div class="space-y-4" style="max-width:860px;">

        <!-- ── Card 1 : Set Customer OTP ────────────────────────────── -->
        <div class="bg-white rounded-lg shadow-sm" style="border:1px solid #e4e4e4;">
            <!-- Card Title -->
            <div class="px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #e4e4e4;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2.5">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
                <h3 class="text-sm font-bold text-gray-800">Set Customer OTP</h3>
            </div>
            <!-- Card Body -->
            <div class="p-5">
                <div class="flex items-start gap-2 mb-3">
                    <span style="color:#374151;font-size:18px;line-height:1;">•</span>
                    <span class="text-sm font-semibold text-gray-800">Scratch &amp; Win</span>
                </div>
                <div class="flex items-center gap-3 pl-5">
                    <span style="color:#374151;font-size:18px;line-height:1;">•</span>
                    <span class="text-sm text-gray-700">
                        Scratch Customers OTP verification is
                        <strong id="otp-label">{{ $otpEnabled }}</strong>
                    </span>
                    <!-- Toggle -->
                    <button id="otp-toggle" onclick="toggleOtp()"
                        style="position:relative;display:inline-flex;align-items:center;width:48px;height:26px;border-radius:9999px;border:none;cursor:pointer;transition:background .25s;background:{{ $otpEnabled === 'Enabled' ? '#038b07a8' : '#d1d5db' }};"
                        title="{{ $otpEnabled === 'Enabled' ? 'Disable OTP' : 'Enable OTP' }}">
                        <span id="otp-knob" style="position:absolute;top:3px;width:20px;height:20px;border-radius:50%;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.3);transition:left .25s;left:{{ $otpEnabled === 'Enabled' ? '25px' : '3px' }};"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ── Card 2 : Connect CRM API ──────────────────────────────── -->
        <div class="bg-white rounded-lg shadow-sm" style="border:1px solid #e4e4e4;">
            <!-- Card Title -->
            <div class="px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #e4e4e4;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2.5">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
                <h3 class="text-sm font-bold text-gray-800">Connect CRM API</h3>
            </div>
            <!-- Card Body -->
            <div class="p-5 space-y-4">
                <!-- Section label -->
                <div class="flex items-start gap-2">
                    <span style="color:#374151;font-size:18px;line-height:1;">•</span>
                    <span class="text-sm font-semibold text-gray-800">To insert customer details to Getlead CRM</span>
                </div>

                <!-- Token input -->
                <div class="pl-5 space-y-2">
                    <div class="flex items-start gap-2">
                        <span style="color:#374151;font-size:18px;line-height:1;">•</span>
                        <span class="text-sm text-gray-600">Enter Your CRM Account API token here and Enable this option</span>
                    </div>
                    <div class="flex gap-2 items-center" id="crm-token-row" style="max-width:580px;">
                        <input type="text" id="crm_token" value="{{ $crmToken }}"
                            class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400"
                            placeholder="Enter API token">
                        <button id="btn-crm-submit" onclick="saveCrmToken()"
                            class="inline-flex items-center justify-center h-9 px-5 text-sm font-medium text-white rounded-md"
                            style="background:#353536;border:none;cursor:pointer;white-space:nowrap;">
                            Submit
                        </button>
                        <button id="btn-crm-remove" onclick="removeCrmToken()"
                            class="inline-flex items-center justify-center gap-1.5 h-9 px-4 text-sm font-medium text-white rounded-md"
                            style="background:#979393;border:none;cursor:pointer;white-space:nowrap;{{ $crmToken ? '' : 'display:none;' }}">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6m4-6v6"/><path d="M9 6V4h6v2"/>
                            </svg>
                            Remove
                        </button>
                    </div>
                </div>

                <!-- CRM enabled toggle -->
                <div class="flex items-center gap-3 pl-5">
                    <span style="color:#374151;font-size:18px;line-height:1;">•</span>
                    <span class="text-sm text-gray-700">
                        This service is
                        <strong id="crm-label">{{ $crmEnabled }}</strong>
                    </span>
                    <!-- Toggle -->
                    <button id="crm-toggle" onclick="toggleCrm()"
                        style="position:relative;display:inline-flex;align-items:center;width:48px;height:26px;border-radius:9999px;border:none;cursor:pointer;transition:background .25s;background:{{ $crmEnabled === 'Enabled' ? '#038b07a8' : '#d1d5db' }};"
                        title="{{ $crmEnabled === 'Enabled' ? 'Disable CRM' : 'Enable CRM' }}">
                        <span id="crm-knob" style="position:absolute;top:3px;width:20px;height:20px;border-radius:50%;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.3);transition:left .25s;left:{{ $crmEnabled === 'Enabled' ? '25px' : '3px' }};"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Mobile responsive overrides -->
<style>
@media (max-width: 640px) {
    /* CRM token input + buttons — stack vertically */
    #crm-token-row {
        flex-direction: column;
        align-items: stretch;
        max-width: 100% !important;
    }
    #crm-token-row input,
    #crm-token-row button {
        width: 100%;
    }

    /* Toggle switches — prevent flexbox from stretching them */
    #otp-toggle,
    #crm-toggle {
        flex-shrink: 0;
        width: 48px !important;
        min-width: 48px !important;
        height: 26px !important;
    }
}
</style>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

<script>
var csrfToken = '{{ csrf_token() }}';

// ── OTP Toggle ─────────────────────────────────────────────────
function toggleOtp() {
    $.ajax({
        url: "{{ route('user.settings.general.toggle-otp') }}",
        type: 'POST',
        data: { _token: csrfToken },
        success: function (res) {
            if (res.success) {
                var on = res.status === 'Enabled';
                $('#otp-toggle').css('background', on ? '#2563eb' : '#d1d5db');
                $('#otp-knob').css('left', on ? '25px' : '3px');
                $('#otp-label').text(res.status);
                showNotification('success', res.message);
            }
        },
        error: function () { showNotification('error', 'Failed to update OTP setting.'); }
    });
}

// ── CRM Token Save ─────────────────────────────────────────────
function saveCrmToken() {
    var token = $('#crm_token').val().trim();
    if (!token) {
        showNotification('error', 'Please enter a CRM API token.');
        return;
    }
    $('#btn-crm-submit').prop('disabled', true).text('Saving...');
    $.ajax({
        url: "{{ route('user.settings.general.crm-token') }}",
        type: 'POST',
        data: { _token: csrfToken, crm_api_token: token },
        success: function (res) {
            $('#btn-crm-submit').prop('disabled', false).text('Submit');
            if (res.success) {
                // Auto-enable the CRM toggle
                $('#crm-toggle').css('background', '#2563eb');
                $('#crm-knob').css('left', '25px');
                $('#crm-label').text('Enabled');
                // Show remove button
                $('#btn-crm-remove').show();
                showNotification('success', res.message);
            } else {
                showNotification('error', res.message);
            }
        },
        error: function (xhr) {
            $('#btn-crm-submit').prop('disabled', false).text('Submit');
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to save CRM token.';
            showNotification('error', msg);
        }
    });
}

// ── CRM Token Remove ───────────────────────────────────────────
function removeCrmToken() {
    $('#btn-crm-remove').prop('disabled', true).text('Removing...');
    $.ajax({
        url: "{{ route('user.settings.general.remove-crm') }}",
        type: 'POST',
        data: { _token: csrfToken },
        success: function (res) {
            $('#btn-crm-remove').prop('disabled', false).text('Remove');
            if (res.success) {
                // Clear input and hide remove button
                $('#crm_token').val('');
                $('#btn-crm-remove').hide();
                // Disable the CRM toggle
                $('#crm-toggle').css('background', '#d1d5db');
                $('#crm-knob').css('left', '3px');
                $('#crm-label').text('Disabled');
                showNotification('success', res.message);
            }
        },
        error: function () {
            $('#btn-crm-remove').prop('disabled', false).text('Remove');
            showNotification('error', 'Failed to remove CRM token.');
        }
    });
}

// ── CRM Toggle ─────────────────────────────────────────────────
function toggleCrm() {
    $.ajax({
        url: "{{ route('user.settings.general.toggle-crm') }}",
        type: 'POST',
        data: { _token: csrfToken },
        success: function (res) {
            if (res.success) {
                var on = res.status === 'Enabled';
                $('#crm-toggle').css('background', on ? '#2563eb' : '#d1d5db');
                $('#crm-knob').css('left', on ? '25px' : '3px');
                $('#crm-label').text(res.status);
                showNotification('success', res.message);
            }
        },
        error: function () { showNotification('error', 'Failed to update CRM setting.'); }
    });
}

// ── Notification ───────────────────────────────────────────────
function showNotification(type, message) {
    var bg = type === 'success' ? '#16a34a' : '#dc2626';
    var el = $('<div style="position:fixed;top:16px;right:16px;z-index:9999;padding:12px 20px;border-radius:8px;color:#fff;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,.15);background:' + bg + ';">' + message + '</div>');
    $('body').append(el);
    setTimeout(function () { el.fadeOut(300, function () { el.remove(); }); }, 3000);
}
</script>

@endsection
