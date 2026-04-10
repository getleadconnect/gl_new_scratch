@extends('layouts.guest')

<!-- Country Code Picker CSS -->
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">

<style>
    /* International Telephone Input Styling */
    .iti {
        width: 100%;
        display: block;
    }

    .iti__flag-container {
        padding: 0;
    }

    .iti__selected-flag {
        padding: 0 8px;
        border-right: 1px solid #d1d5db;
    }

    #mobileNumber {
        padding-left: 52px;
    }

    .iti__country-list {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        max-height: 200px;
    }

    .iti__country:hover {
        background-color: #f3f4f6;
    }

    .iti__country.iti__highlight {
        background-color: #3b82f6;
    }

.logo-container {
  display: flex;
  justify-content: center; /* center horizontally */
  align-items: center;     /* center vertically */
  gap: 10px;               /* space between items */
}


</style>

@section('content')
<div>
    <div class="logo-container">
        <div >
            <img src="{{url('/assets/img/gl_logo.png')}}" style="width:40px;"> &nbsp;
        </div>
        <div >
            <h1 class=" logo-item text-3xl font-bold text-center text-foreground mb-6">
                <span style="color:red;">GL</span>-SCRATCH
            </h1>
        </div>
    </div>

    <h2 class="text-xl font-bold text-center text-foreground mb-6">
        Sign in to your account
    </h2>

    <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-6">
        @csrf

        <!-- Mobile Number with Country Code -->
        <div>
            <label for="mobileNumber" class="block text-sm font-medium text-foreground mb-2">
                Mobile Number
            </label>
            <input
                id="mobileNumber"
                name="mobile_full"
                type="tel"
                required
                autofocus
                class="block w-full px-3 py-2 border border-border rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm @error('mobile') border-red-500 @enderror"
                placeholder="Enter your mobile number"
            >
            <input type="hidden" name="country_code" id="countryCode">
            <input type="hidden" name="mobile" id="mobileOnly">
            @error('mobile')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-foreground mb-2">
                Password
            </label>
            <input
                id="password"
                name="password"
                type="password"
                required
                class="block w-full px-3 py-2 border border-border rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm @error('password') border-red-500 @enderror"
                placeholder="••••••••"
            >
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    class="h-4 w-4 text-primary focus:ring-primary border-border rounded"
                >
                <label for="remember" class="ml-2 block text-sm text-muted-foreground">
                    Remember me
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button
                type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors"
            >
                Sign in
            </button>
        </div>

        <!-- Register Link -->
        <div class="text-center">
            <p class="text-sm text-muted-foreground">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-primary hover:text-primary/90">
                    Register here
                </a>
            </p>
        </div>
        
    </form>
</div>

<!-- International Telephone Input JS -->
<script src="{{asset('assets/js/intlTelInput.min.js')}}"></script>

<script>
    // Initialize International Telephone Input
    const phoneInput = document.querySelector("#mobileNumber");
    const iti = window.intlTelInput(phoneInput, {
        initialCountry: "in",
        preferredCountries: ["us", "gb", "in", "ae"],
        separateDialCode: true,
        utilsScript: "{{asset('assets/js/intlTelInput_utils.js')}}"
    });

    // Handle form submission
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        // Get country code and mobile number
        const selectedCountryData = iti.getSelectedCountryData();
        const fullNumber = iti.getNumber();
        const countryCode = '+' + selectedCountryData.dialCode;
        const nationalNumber = fullNumber.replace(countryCode, '').trim();

        // Set hidden fields
        document.getElementById('countryCode').value = countryCode;
        document.getElementById('mobileOnly').value = nationalNumber;
    });

    // Pre-fill mobile number if there's old input (for validation errors)
    @if(old('country_code') && old('mobile'))
        const oldFullNumber = "{{ old('country_code') }}{{ old('mobile') }}";
        iti.setNumber(oldFullNumber);
    @endif
</script>

@endsection
