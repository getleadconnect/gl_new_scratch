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

    <!-- Charts Row: 3 columns -->
    <div style="display:flex;gap:20px;flex-wrap:wrap;">

        <!-- Pie Chart — User Status -->
        <div class="bg-white shadow rounded-lg border border-border" style="flex:1;min-width:250px;">
            <div class="px-4 sm:px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #e4e4e4;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0110 10H12V2z"/>
                </svg>
                <h3 class="text-sm font-bold text-gray-800">User Status</h3>
            </div>
            <div class="p-4 sm:p-5 flex items-center justify-center">
                <div style="max-width:220px;width:100%;">
                    <canvas id="userPieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Chart — Monthly Registrations -->
        <div class="bg-white shadow rounded-lg border border-border" style="flex:1;min-width:250px;">
            <div class="px-4 sm:px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid #e4e4e4;">
                <div class="flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2.5">
                        <rect x="3" y="12" width="4" height="9"/><rect x="9.5" y="7" width="4" height="14"/><rect x="16" y="3" width="4" height="18"/>
                    </svg>
                    <h3 class="text-sm font-bold text-gray-800">Monthly Subscriptions</h3>
                </div>
                <select id="barYearSelect" onchange="loadChartData()"
                    style="font-size:12px;padding:2px 8px;border:1px solid #d1d5db;border-radius:4px;outline:none;">
                    @for($y = now()->year; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="p-4 sm:p-5">
                <canvas id="monthlyBarChart" style="max-height:260px;"></canvas>
            </div>
        </div>

        <!-- Line Chart — Trend Analytics -->
        <div class="bg-white shadow rounded-lg border border-border" style="flex:1;min-width:250px;">
            <div class="px-4 sm:px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid #e4e4e4;">
                <div class="flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2.5">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    <h3 class="text-sm font-bold text-gray-800">Trend Analytics</h3>
                    <span class="ml-1 flex items-center gap-1 text-xs text-gray-600">
                        <span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:#22c55e;"></span> Active
                    </span>
                    <span class="flex items-center gap-1 text-xs text-gray-600">
                        <span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:#f87171;"></span> Expired
                    </span>
                </div>
                <select id="trendYearSelect" onchange="loadChartData()"
                    style="font-size:12px;padding:2px 8px;border:1px solid #d1d5db;border-radius:4px;outline:none;">
                    @for($y = now()->year; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="p-4 sm:p-5">
                <canvas id="trendLineChart" style="max-height:260px;"></canvas>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    // ── Pie Chart — User Status ──
    new Chart(document.getElementById('userPieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Expired'],
            datasets: [{
                data: [{{ $activeUsers }}, {{ $expiredUsers }}],
                backgroundColor: ['#22c55e', '#f87171'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 12 } }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                            var pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                            return ctx.label + ': ' + ctx.parsed.toLocaleString() + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });

    // ── Bar Chart — Monthly Registrations ──
    var barChart = new Chart(document.getElementById('monthlyBarChart'), {
        type: 'bar',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'New Users',
                data: @json($monthlyData),
                backgroundColor: '#6366f1',
                borderRadius: 4,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) { return 'New Users: ' + ctx.parsed.y.toLocaleString(); }
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { beginAtZero: true, ticks: { precision: 0, font: { size: 11 } }, grid: { color: '#f3f4f6' } }
            }
        }
    });

    // ── Line Chart — Trend Analytics ──
    var trendChart = new Chart(document.getElementById('trendLineChart'), {
        type: 'line',
        data: {
            labels: @json($trendLabels),
            datasets: [
                {
                    label: 'Active',
                    data: @json($trendActive),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2,
                },
                {
                    label: 'Expired',
                    data: @json($trendExpired),
                    borderColor: '#f87171',
                    backgroundColor: 'rgba(248,113,113,0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) { return ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString(); }
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { beginAtZero: true, ticks: { precision: 0, font: { size: 11 } }, grid: { color: '#f3f4f6' } }
            }
        }
    });

    // ── Load chart data by year (AJAX) ──
    window.loadChartData = function() {
        var barYear   = document.getElementById('barYearSelect').value;
        var trendYear = document.getElementById('trendYearSelect').value;

        // Update bar chart
        fetch('{{ route("admin.dashboard.chart-data") }}?year=' + barYear)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                barChart.data.labels = data.labels;
                barChart.data.datasets[0].data = data.monthlyData;
                barChart.update();
            });

        // Update trend chart
        fetch('{{ route("admin.dashboard.chart-data") }}?year=' + trendYear)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                trendChart.data.labels = data.labels;
                trendChart.data.datasets[0].data = data.trendActive;
                trendChart.data.datasets[1].data = data.trendExpired;
                trendChart.update();
            });
    };
})();
</script>
@endsection
