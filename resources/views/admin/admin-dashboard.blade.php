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
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Total Scratch</p>
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
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Used Count</p>
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
                        <p class="text-xs sm:text-sm font-medium text-muted-foreground truncate">Balance Count</p>
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


    <!-- Child Users Table -->
    <div class="bg-white shadow rounded-lg border border-border">
        <div class="px-4 sm:px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #e4e4e4;">
            <svg class="w-4 h-4 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h3 class="text-sm font-bold text-gray-800">Child Users</h3>
            <span class="ml-2 text-xs text-muted-foreground">({{ $childUsers->count() }} total)</span>
        </div>
        <div class="p-4 sm:p-5">
            @if($childUsers->isEmpty())
                <p class="text-sm text-muted-foreground text-center py-6">No child users found.</p>
            @else
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="w-full text-sm text-left" style="min-width:500px;">
                        <thead>
                            <tr style="border-bottom:1px solid #e4e4e4;">
                                <th class="pb-3 pl-4 sm:pl-0 pr-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">#</th>
                                <th class="pb-3 pr-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Name</th>
                                <th class="pb-3 pr-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Mobile</th>
                                <th class="pb-3 pr-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Status</th>
                                <th class="pb-3 pr-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider text-right">Total</th>
                                <th class="pb-3 pr-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider text-right">Used</th>
                                <th class="pb-3 pr-4 sm:pr-0 text-xs font-semibold text-muted-foreground uppercase tracking-wider text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($childUsers as $i => $child)
                            <tr style="border-bottom:1px solid #f3f4f6;">
                                <td class="py-3 pl-4 sm:pl-0 pr-3 text-muted-foreground">{{ $i + 1 }}</td>
                                <td class="py-3 pr-3 font-medium text-foreground whitespace-nowrap">{{ $child->name }}</td>
                                <td class="py-3 pr-3 text-muted-foreground whitespace-nowrap">{{ $child->mobile }}</td>
                                <td class="py-3 pr-3">
                                    @if($child->status == 1)
                                        <span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#dcfce7;color:#166534;">Active</span>
                                    @else
                                        <span style="padding:2px 8px;display:inline-flex;font-size:11px;font-weight:600;border-radius:9999px;background:#f3f4f6;color:#991b1b;">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-3 text-right font-semibold text-foreground">{{ number_format($child->total_count ?? 0) }}</td>
                                <td class="py-3 pr-3 text-right text-orange-600 font-semibold">{{ number_format($child->used_count ?? 0) }}</td>
                                <td class="py-3 pr-4 sm:pr-0 text-right text-green-600 font-semibold">{{ number_format($child->balance_count ?? 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>

@if(count($chartLabels) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    var labels   = @json($chartLabels);
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
