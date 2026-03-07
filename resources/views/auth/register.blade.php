@extends('layouts.guest')

@section('content')
<div>
    <h2 class="text-2xl font-bold text-center text-foreground mb-6">
        Create your account
    </h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-foreground mb-2">
                Full Name
            </label>
            <input
                id="name"
                name="name"
                type="text"
                required
                autofocus
                value="{{ old('name') }}"
                class="block w-full px-3 py-2 border border-border rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm @error('name') border-red-500 @enderror"
                placeholder="John Doe"
            >
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-foreground mb-2">
                Email address
            </label>
            <input
                id="email"
                name="email"
                type="email"
                required
                value="{{ old('email') }}"
                class="block w-full px-3 py-2 border border-border rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm @error('email') border-red-500 @enderror"
                placeholder="you@example.com"
            >
            @error('email')
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

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-foreground mb-2">
                Confirm Password
            </label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                class="block w-full px-3 py-2 border border-border rounded-md shadow-sm placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm"
                placeholder="••••••••"
            >
        </div>

        <!-- Submit Button -->
        <div>
            <button
                type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors"
            >
                Create Account
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-sm text-muted-foreground">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary/90">
                    Sign in here
                </a>
            </p>
        </div>
    </form>
</div>
@endsection
