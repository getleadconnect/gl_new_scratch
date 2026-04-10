@extends('layouts.user')

@section('content')
<div class="space-y-4">

    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-1 text-sm text-muted-foreground">Manage your account details and password</p>
    </div>

    <!-- Split Panel -->
    <div class="flex gap-5 items-start" id="profile-split-panel">

        <!-- ── Left Panel : Profile Information ───────────────────── -->
        <div class="bg-white rounded-lg shadow-sm flex-1" style="border:1px solid #e4e4e4;">
            <div class="px-5 py-3" style="border-bottom:1px solid #e4e4e4;">
                <h3 class="text-sm font-semibold text-gray-800">Profile Information :</h3>
            </div>
            <div class="p-5 space-y-4">

                <!-- Name -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Full Name <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="text" id="profile_name" value="{{ old('name', $user->name) }}" maxlength="255"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                        placeholder="Full name">
                    <p id="profile_name_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Email Address <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="email" id="profile_email" value="{{ old('email', $user->email) }}" maxlength="255"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                        placeholder="Email address">
                    <p id="profile_email_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
                </div>

                <!-- Company Name -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Company Name</label>
                    <input type="text" id="profile_company" value="{{ old('company_name', $user->company_name) }}" maxlength="255"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                        placeholder="Company name">
                </div>

                <!-- Mobile -->
                <div class="flex gap-3">
                    <div style="width:110px;">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Country Code</label>
                        <input type="text" id="profile_country_code" value="{{ old('country_code', $user->country_code) }}" maxlength="10"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                            placeholder="+91">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Mobile Number</label>
                        <input type="text" id="profile_mobile" value="{{ old('mobile', $user->mobile) }}" maxlength="20"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                            placeholder="Mobile number">
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Address</label>
                    <textarea id="profile_address" rows="3" maxlength="500"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                        placeholder="Address">{{ old('address', $user->address) }}</textarea>
                </div>

                <!-- Save Button -->
                <div class="pt-1">
                    <button id="btn-save-profile"
                        class="inline-flex items-center justify-center h-9 px-6 text-sm font-medium rounded-md"
                        style="background:#18181b;color:#fff;border:none;cursor:pointer;">
                        Save Changes
                    </button>
                </div>

            </div>
        </div>

        <!-- ── Right Panel : Change Password ──────────────────────── -->
        <div class="bg-white rounded-lg shadow-sm" style="width:400px;flex-shrink:0;border:1px solid #e4e4e4;">
            <div class="px-5 py-3" style="border-bottom:1px solid #e4e4e4;">
                <h3 class="text-sm font-semibold text-gray-800">Change Password :</h3>
            </div>
            <div class="p-5 space-y-4">

                <!-- New Password -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        New Password <span style="color:#dc2626;">*</span>
                    </label>
                    <div style="position:relative;">
                        <input type="password" id="new_password" maxlength="255"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                            placeholder="New password (min 8 characters)" style="padding-right:38px;">
                        <span onclick="togglePwd('new_password', this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;color:#9ca3af;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </span>
                    </div>
                    <p id="new_password_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Confirm New Password <span style="color:#dc2626;">*</span>
                    </label>
                    <div style="position:relative;">
                        <input type="password" id="new_password_confirmation" maxlength="255"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                            placeholder="Confirm new password" style="padding-right:38px;">
                        <span onclick="togglePwd('new_password_confirmation', this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;color:#9ca3af;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </span>
                    </div>
                    <p id="confirm_password_error" class="text-xs mt-1" style="color:#dc2626;display:none;"></p>
                </div>

                <!-- Update Button -->
                <div class="pt-1">
                    <button id="btn-change-password"
                        class="inline-flex items-center justify-center h-9 px-6 text-sm font-medium rounded-md"
                        style="background:#18181b;color:#fff;border:none;cursor:pointer;">
                        Update Password
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Mobile responsive overrides -->
<style>
@media (max-width: 640px) {
    /* Stack panels vertically */
    #profile-split-panel {
        flex-direction: column;
    }

    /* Right panel — override fixed 400px width */
    #profile-split-panel > div:last-child {
        width: 100% !important;
        flex-shrink: unset;
    }

    /* Save / Update buttons full width */
    #btn-save-profile,
    #btn-change-password {
        width: 100%;
    }
}
</style>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

<script>
// ── Toggle password visibility ─────────────────────────────────
function togglePwd(fieldId, icon) {
    var input = document.getElementById(fieldId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.style.color = '#374151';
    } else {
        input.type = 'password';
        icon.style.color = '#9ca3af';
    }
}

$(document).ready(function () {

    // ── Save Profile ───────────────────────────────────────────
    $('#btn-save-profile').on('click', function () {
        // Clear errors
        $('#profile_name_error, #profile_email_error').hide().text('');

        var valid = true;
        if (!$('#profile_name').val().trim()) {
            $('#profile_name_error').text('Full name is required.').show();
            valid = false;
        }
        if (!$('#profile_email').val().trim()) {
            $('#profile_email_error').text('Email address is required.').show();
            valid = false;
        }
        if (!valid) return;

        $('#btn-save-profile').prop('disabled', true).text('Saving...');

        $.ajax({
            url: "{{ route('user.settings.profile.update') }}",
            type: 'POST',
            data: {
                _token:        '{{ csrf_token() }}',
                name:          $('#profile_name').val().trim(),
                email:         $('#profile_email').val().trim(),
                company_name:  $('#profile_company').val().trim(),
                country_code:  $('#profile_country_code').val().trim(),
                mobile:        $('#profile_mobile').val().trim(),
                address:       $('#profile_address').val().trim(),
            },
            success: function (res) {
                $('#btn-save-profile').prop('disabled', false).text('Save Changes');
                if (res.success) {
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                $('#btn-save-profile').prop('disabled', false).text('Save Changes');
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    if (errors.name)  $('#profile_name_error').text(errors.name[0]).show();
                    if (errors.email) $('#profile_email_error').text(errors.email[0]).show();
                } else {
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to update profile.';
                    showNotification('error', msg);
                }
            }
        });
    });

    // ── Change Password ────────────────────────────────────────
    $('#btn-change-password').on('click', function () {
        $('#new_password_error, #confirm_password_error').hide().text('');

        var valid = true;
        var newPwd     = $('#new_password').val();
        var confirmPwd = $('#new_password_confirmation').val();

        if (!newPwd) {
            $('#new_password_error').text('New password is required.').show();
            valid = false;
        } else if (newPwd.length < 8) {
            $('#new_password_error').text('Password must be at least 8 characters.').show();
            valid = false;
        }
        if (!confirmPwd) {
            $('#confirm_password_error').text('Please confirm your new password.').show();
            valid = false;
        } else if (newPwd && confirmPwd !== newPwd) {
            $('#confirm_password_error').text('Passwords do not match.').show();
            valid = false;
        }
        if (!valid) return;

        $('#btn-change-password').prop('disabled', true).text('Updating...');

        $.ajax({
            url: "{{ route('user.settings.profile.password') }}",
            type: 'POST',
            data: {
                _token:                    '{{ csrf_token() }}',
                new_password:              newPwd,
                new_password_confirmation: confirmPwd,
            },
            success: function (res) {
                $('#btn-change-password').prop('disabled', false).text('Update Password');
                if (res.success) {
                    $('#new_password, #new_password_confirmation').val('');
                    showNotification('success', res.message);
                } else {
                    showNotification('error', res.message);
                }
            },
            error: function (xhr) {
                $('#btn-change-password').prop('disabled', false).text('Update Password');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to change password.';
                showNotification('error', msg);
            }
        });
    });

});

function showNotification(type, message) {
    var bg = type === 'success' ? '#16a34a' : '#dc2626';
    var el = $('<div style="position:fixed;top:16px;right:16px;z-index:9999;padding:12px 20px;border-radius:8px;color:#fff;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,.15);background:' + bg + ';">' + message + '</div>');
    $('body').append(el);
    setTimeout(function () { el.fadeOut(300, function () { el.remove(); }); }, 3000);
}
</script>

@endsection
