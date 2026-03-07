@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-1 text-sm text-muted-foreground">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">

        <!-- Total Users -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-border">
            <div class="p-4 sm:p-5">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center" style="background:#ede9fe;">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Total Users</p>
                        <p class="text-xl sm:text-2xl font-bold text-foreground">{{ number_format($totalUsers) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-border">
            <div class="p-4 sm:p-5">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center" style="background:#dcfce7;">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Active Users</p>
                        <p class="text-xl sm:text-2xl font-bold text-foreground">{{ number_format($activeUsers) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expired Users -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-border">
            <div class="p-4 sm:p-5">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center" style="background:#fee2e2;">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Expired Users</p>
                        <p class="text-xl sm:text-2xl font-bold text-foreground">{{ number_format($expiredUsers) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Scratch Count -->
        <div class="bg-white overflow-hidden shadow rounded-lg border border-border">
            <div class="p-4 sm:p-5">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center" style="background:#dbeafe;">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Total Scratch</p>
                        <p class="text-xl sm:text-2xl font-bold text-foreground">{{ number_format($totalScratch) }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
