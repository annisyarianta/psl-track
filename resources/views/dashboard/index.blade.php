@extends('layouts.app')

@section('title')
    Dashboard | PSL Track
@endsection

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        .psl-dash {
            --psl-dash-indigo-start: #4f46e5;
            --psl-dash-indigo-end: #8b5cf6;
            --psl-dash-teal-start: #0d9488;
            --psl-dash-teal-end: #22d3ee;
            --psl-dash-amber-start: #f59e0b;
            --psl-dash-amber-end: #fb923c;
            --psl-dash-rose-start: #e11d48;
            --psl-dash-rose-end: #fb7185;
            --psl-dash-card-border: rgba(124, 58, 237, 0.30);
            --psl-dash-track: rgba(127, 127, 127, 0.16);
            --psl-dash-radius: 20px;
            font-family: 'Plus Jakarta Sans', var(--bs-body-font-family, sans-serif);
            padding-bottom: 30px;
        }

        .psl-dash * {
            box-sizing: border-box;
        }

        /* ---------- Hero ---------- */
        .psl-dash-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            padding: 28px 32px;
            margin-bottom: 20px;
            color: #fff;
            border-radius: 24px;
            background: linear-gradient(120deg, #1e1b4b 0%, #4c1d95 48%, #7c3aed 100%);
        }

        .psl-dash-hero-text {
            position: relative;
            z-index: 2;
            max-width: 560px;
        }

        .psl-dash-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.28);
        }

        .psl-dash-hero-title {
            margin: 14px 0 6px;
            font-size: 30px;
            font-weight: 800;
            line-height: 1.2;
            color: #fff;
        }

        .psl-dash-hero-subtitle {
            margin: 0;
            font-size: 15px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.82);
        }

        .psl-dash-hero-art {
            position: absolute;
            right: -10px;
            top: -20px;
            width: 380px;
            height: 260px;
            z-index: 1;
            pointer-events: none;
        }

        .psl-dash-filter {
            position: relative;
            z-index: 2;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .psl-dash-filter-field {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
        }

        .psl-dash-filter-field select {
            min-width: 110px;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background-color: rgba(255, 255, 255, 0.14);
        }

        .psl-dash-filter-field select option {
            color: #1f2937;
        }

        /* ---------- Kartu umum ---------- */
        .psl-dash .psl-dash-card {
            border: 2px solid var(--psl-dash-card-border);
            border-radius: var(--psl-dash-radius);
            margin-bottom: 0;
            box-shadow: 0 6px 24px rgba(79, 70, 229, 0.08);
        }

        .psl-dash .psl-dash-summary-grid .psl-dash-card {
            border-color: color-mix(in srgb, var(--psl-dash-tone-start) 45%, transparent);
        }

        .psl-dash-card-body {
            padding: 22px 24px;
        }

        .psl-dash-card-title {
            margin: 0 0 4px;
            font-size: 17px;
            font-weight: 700;
        }

        .psl-dash-card-caption {
            margin: 0 0 16px;
            font-size: 13px;
            color: var(--bs-secondary-color, #6b7280);
        }

        /* ---------- Kartu ringkasan ---------- */
        .psl-dash-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .psl-dash-summary-body {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
        }

        .psl-dash-summary-icon {
            flex: 0 0 56px;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--psl-dash-tone-start), var(--psl-dash-tone-end));
        }

        .psl-dash-summary-value {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
            line-height: 1.1;
        }

        .psl-dash-summary-label {
            margin: 2px 0 0;
            font-size: 14px;
            font-weight: 600;
        }

        .psl-dash-summary-note {
            margin: 2px 0 0;
            font-size: 12px;
            color: var(--bs-secondary-color, #6b7280);
        }

        .psl-dash-tone-indigo {
            --psl-dash-tone-start: var(--psl-dash-indigo-start);
            --psl-dash-tone-end: var(--psl-dash-indigo-end);
        }

        .psl-dash-tone-teal {
            --psl-dash-tone-start: var(--psl-dash-teal-start);
            --psl-dash-tone-end: var(--psl-dash-teal-end);
        }

        .psl-dash-tone-amber {
            --psl-dash-tone-start: var(--psl-dash-amber-start);
            --psl-dash-tone-end: var(--psl-dash-amber-end);
        }

        .psl-dash-tone-rose {
            --psl-dash-tone-start: var(--psl-dash-rose-start);
            --psl-dash-tone-end: var(--psl-dash-rose-end);
        }

        /* ---------- Grafik ---------- */
        .psl-dash-chart-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .psl-dash-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: 500;
        }

        .psl-dash-legend-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 6px;
            border-radius: 3px;
        }

        .psl-dash-chart-box {
            position: relative;
            height: 260px;
        }

        .psl-dash-donut-center {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .psl-dash-donut-center strong {
            font-size: 32px;
            font-weight: 800;
            line-height: 1;
        }

        .psl-dash-donut-center span {
            font-size: 13px;
            color: var(--bs-secondary-color, #6b7280);
        }

        /* ---------- Perbandingan dan perhatian ---------- */
        .psl-dash-bottom-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .psl-dash-ranking-item {
            margin-bottom: 16px;
        }

        .psl-dash-ranking-item:last-child {
            margin-bottom: 0;
        }

        .psl-dash-ranking-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        .psl-dash-ranking-percent {
            flex: 0 0 auto;
            font-weight: 800;
        }

        .psl-dash-ranking-track {
            height: 10px;
            overflow: hidden;
            border-radius: 999px;
            background: var(--psl-dash-track);
        }

        .psl-dash-ranking-fill {
            height: 100%;
            border-radius: 999px;
        }

        .psl-dash-fill-high {
            background: linear-gradient(90deg, #059669, #34d399);
        }

        .psl-dash-fill-mid {
            background: linear-gradient(90deg, #f59e0b, #fcd34d);
        }

        .psl-dash-fill-low {
            background: linear-gradient(90deg, #e11d48, #fb7185);
        }

        .psl-dash-ranking-detail {
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--bs-secondary-color, #6b7280);
        }

        .psl-dash-attention-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 0;
            border-top: 1px solid var(--psl-dash-track);
        }

        .psl-dash-attention-item:first-child {
            border-top: 0;
            padding-top: 0;
        }

        .psl-dash-attention-icon {
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            border-radius: 14px;
        }

        .psl-dash-attention-warning {
            color: #b45309;
            background: rgba(245, 158, 11, 0.18);
        }

        .psl-dash-attention-danger {
            color: #be123c;
            background: rgba(225, 29, 72, 0.15);
        }

        .psl-dash-attention-info {
            color: #4338ca;
            background: rgba(99, 102, 241, 0.16);
        }

        .psl-dash-attention-title {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }

        .psl-dash-attention-description {
            margin: 2px 0 0;
            font-size: 13px;
            color: var(--bs-secondary-color, #6b7280);
        }

        .psl-dash-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 28px 12px;
            text-align: center;
            color: var(--bs-secondary-color, #6b7280);
        }

        .psl-dash-empty i {
            font-size: 40px;
            color: #10b981;
        }

        .psl-dash-empty-muted i {
            color: #9ca3af;
        }

        .psl-dash-empty strong {
            font-size: 15px;
            color: var(--bs-body-color);
        }

        /* ---------- Responsive ---------- */
        @media (max-width: 1199.98px) {
            .psl-dash-summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 991.98px) {

            .psl-dash-chart-grid,
            .psl-dash-bottom-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .psl-dash-hero-art {
                width: 300px;
                opacity: 0.55;
            }
        }

        @media (max-width: 575.98px) {
            .psl-dash-hero {
                padding: 22px 20px;
                border-radius: 20px;
            }

            .psl-dash-hero-title {
                font-size: 24px;
            }

            .psl-dash-hero-art {
                display: none;
            }

            .psl-dash-filter,
            .psl-dash-filter-field {
                width: 100%;
            }

            .psl-dash-filter-field select {
                width: 100%;
            }

            .psl-dash-summary-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .psl-dash-card-body {
                padding: 18px;
            }

            .psl-dash-chart-box {
                height: 230px;
            }
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="psl-dash">
                    {{-- ===================== HERO ===================== --}}
                    <section class="psl-dash-hero">
                        <svg class="psl-dash-hero-art" viewBox="0 0 380 260" fill="none" aria-hidden="true">
                            <circle cx="300" cy="80" r="120" fill="#8b5cf6" fill-opacity="0.35" />
                            <circle cx="300" cy="80" r="82" stroke="#fff" stroke-opacity="0.35"
                                stroke-width="2" />
                            <circle cx="300" cy="80" r="46" stroke="#fff" stroke-opacity="0.55"
                                stroke-width="2" />
                            <circle cx="300" cy="80" r="14" fill="#fbbf24" />
                            <rect x="170" y="150" width="26" height="70" rx="8" fill="#22d3ee"
                                fill-opacity="0.85" />
                            <rect x="206" y="120" width="26" height="100" rx="8" fill="#a78bfa" />
                            <rect x="242" y="92" width="26" height="128" rx="8" fill="#fb7185"
                                fill-opacity="0.9" />
                            <path d="M160 168 L212 128 L250 140 L322 56" stroke="#fbbf24" stroke-width="4"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="322" cy="56" r="7" fill="#fbbf24" />
                        </svg>

                        <div class="psl-dash-hero-text">
                            <span class="psl-dash-role-badge">
                                <i class="mdi mdi-shield-account-outline"></i> {{ $roleLabel }}
                            </span>
                            <h1 class="psl-dash-hero-title">{{ $greeting }}, {{ $greetingName }}!</h1>
                            <p class="psl-dash-hero-subtitle">
                                @if ($hasData)
                                    Berikut ringkasan capaian KPI {{ $scopeDescription }} untuk {{ $selectedPeriodLabel }}
                                    tahun
                                    {{ $selectedYear->tahun }}.
                                @else
                                    Data KPI {{ $scopeDescription }} akan tampil di sini setelah periode triwulan tersedia.
                                @endif
                            </p>
                        </div>

                        @if ($availableYears->isNotEmpty())
                            <form method="GET" action="{{ route('dashboard') }}" class="psl-dash-filter"
                                id="dashboardFilterForm">
                                <label class="psl-dash-filter-field">
                                    Tahun
                                    <select name="tahun" class="form-select">
                                        @foreach ($availableYears as $yearOption)
                                            <option value="{{ $yearOption->id_tahun }}" @selected($selectedYear && $yearOption->id_tahun === $selectedYear->id_tahun)>
                                                {{ $yearOption->tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>
                                @if ($hasData)
                                    <label class="psl-dash-filter-field">
                                        Triwulan
                                        <select name="triwulan" class="form-select">
                                            @foreach ($periodOptions as $periodOption)
                                                <option value="{{ $periodOption['id'] }}" @selected($periodOption['id'] === $selectedPeriod->id_periode_tw)>
                                                    {{ $periodOption['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </label>
                                @endif
                            </form>
                        @endif
                    </section>

                    @if ($hasData)
                        {{-- ===================== KARTU RINGKASAN ===================== --}}
                        <section class="psl-dash-summary-grid">
                            @foreach ($summaryCards as $summaryCard)
                                <div class="card psl-dash-card psl-dash-tone-{{ $summaryCard['tone'] }}">
                                    <div class="psl-dash-summary-body">
                                        <div class="psl-dash-summary-icon"><i class="mdi {{ $summaryCard['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <p class="psl-dash-summary-value">{{ $summaryCard['value'] }}</p>
                                            <p class="psl-dash-summary-label">{{ $summaryCard['label'] }}</p>
                                            <p class="psl-dash-summary-note">{{ $summaryCard['note'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </section>

                        {{-- ===================== GRAFIK ===================== --}}
                        <section class="psl-dash-chart-grid">
                            <div class="card psl-dash-card">
                                <div class="psl-dash-card-body">
                                    <h2 class="psl-dash-card-title">Status monitoring per triwulan</h2>
                                    <p class="psl-dash-card-caption">Jumlah monitoring indikator pada tahun
                                        {{ $selectedYear->tahun }}.
                                    </p>
                                    <div class="psl-dash-legend">
                                        <span><i class="psl-dash-legend-dot" style="background:#10b981"></i>Selesai</span>
                                        <span><i class="psl-dash-legend-dot" style="background:#f59e0b"></i>Berjalan</span>
                                        <span><i class="psl-dash-legend-dot" style="background:#cbd5e1"></i>Belum
                                            mulai</span>
                                    </div>
                                    <div class="psl-dash-chart-box">
                                        <canvas id="monitoringStatusChartCanvas" role="img"
                                            aria-label="Diagram batang status monitoring per triwulan"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="card psl-dash-card">
                                <div class="psl-dash-card-body">
                                    <h2 class="psl-dash-card-title">Status task</h2>
                                    <p class="psl-dash-card-caption">Task yang berkaitan dengan Anda.</p>
                                    <div class="psl-dash-legend">
                                        <span><i class="psl-dash-legend-dot" style="background:#cbd5e1"></i>Todo</span>
                                        <span><i class="psl-dash-legend-dot"
                                                style="background:#6366f1"></i>Berjalan</span>
                                        <span><i class="psl-dash-legend-dot" style="background:#10b981"></i>Selesai</span>
                                    </div>
                                    @if ($totalTaskCount > 0)
                                        <div class="psl-dash-chart-box">
                                            <canvas id="taskStatusChartCanvas" role="img"
                                                aria-label="Diagram donat status task"></canvas>
                                            <div class="psl-dash-donut-center">
                                                <strong>{{ $totalTaskCount }}</strong><span>total
                                                    task</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="psl-dash-empty psl-dash-empty-muted">
                                            <i class="mdi mdi-clipboard-text-outline"></i>
                                            <strong>Belum ada task</strong>
                                            <span>Task yang diberikan atau diterima akan muncul di sini.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>

                        {{-- ===================== PERBANDINGAN DAN PERHATIAN ===================== --}}
                        <section class="psl-dash-bottom-grid">
                            <div class="card psl-dash-card">
                                <div class="psl-dash-card-body">
                                    <h2 class="psl-dash-card-title">{{ $rankingTitle }}</h2>
                                    <p class="psl-dash-card-caption">Kondisi {{ $selectedPeriodLabel }} tahun
                                        {{ $selectedYear->tahun }}.</p>

                                    @forelse ($rankingItems as $rankingItem)
                                        @php
                                            $fillClass =
                                                $rankingItem['percent'] >= 75
                                                    ? 'psl-dash-fill-high'
                                                    : ($rankingItem['percent'] >= 50
                                                        ? 'psl-dash-fill-mid'
                                                        : 'psl-dash-fill-low');
                                        @endphp
                                        <div class="psl-dash-ranking-item">
                                            <div class="psl-dash-ranking-head">
                                                <span>{{ $rankingItem['label'] }}</span>
                                                <span
                                                    class="psl-dash-ranking-percent">{{ $rankingItem['percent'] }}%</span>
                                            </div>
                                            <div class="psl-dash-ranking-track">
                                                <div class="psl-dash-ranking-fill {{ $fillClass }}"
                                                    style="width: {{ $rankingItem['percent'] }}%"></div>
                                            </div>
                                            <p class="psl-dash-ranking-detail">{{ $rankingItem['detail'] }}</p>
                                        </div>
                                    @empty
                                        <div class="psl-dash-empty psl-dash-empty-muted">
                                            <i class="mdi mdi-chart-bar"></i>
                                            <strong>Belum ada data</strong>
                                            <span>Data muncul setelah indikator di-assign dan monitoring dibuat.</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="card psl-dash-card">
                                <div class="psl-dash-card-body">
                                    <h2 class="psl-dash-card-title">Perlu perhatian</h2>
                                    <p class="psl-dash-card-caption">Hal yang sebaiknya ditindaklanjuti lebih dulu.</p>

                                    @forelse ($attentionItems as $attentionItem)
                                        <div class="psl-dash-attention-item">
                                            <div
                                                class="psl-dash-attention-icon psl-dash-attention-{{ $attentionItem['tone'] }}">
                                                <i class="mdi {{ $attentionItem['icon'] }}"></i>
                                            </div>
                                            <div>
                                                <p class="psl-dash-attention-title">{{ $attentionItem['title'] }}</p>
                                                <p class="psl-dash-attention-description">
                                                    {{ $attentionItem['description'] }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="psl-dash-empty">
                                            <i class="mdi mdi-check-decagram-outline"></i>
                                            <strong>Semua aman</strong>
                                            <span>Tidak ada yang perlu ditindaklanjuti saat ini.</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </section>
                    @else
                        <div class="card psl-dash-card">
                            <div class="psl-dash-empty psl-dash-empty-muted">
                                <i class="mdi mdi-calendar-blank-outline"></i>
                                <strong>Periode triwulan belum tersedia</strong>
                                <span>Tambahkan tahun dan periode triwulan terlebih dahulu.</span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @if ($hasData)
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            (function() {
                const monitoringChartData = @json($monitoringChartData);
                const taskChartData = @json($taskChartData);

                const textColor = getComputedStyle(document.body).color;
                const gridLineColor = 'rgba(127, 127, 127, 0.18)';
                const animationOption = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? false : {
                    duration: 700
                };

                Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
                Chart.defaults.color = textColor;

                const filterForm = document.getElementById('dashboardFilterForm');
                if (filterForm) {
                    filterForm.querySelectorAll('select').forEach(function(selectField) {
                        selectField.addEventListener('change', function() {
                            filterForm.submit();
                        });
                    });
                }

                new Chart(document.getElementById('monitoringStatusChartCanvas'), {
                    type: 'bar',
                    data: {
                        labels: monitoringChartData.labels,
                        datasets: [{
                                label: 'Selesai',
                                data: monitoringChartData.done,
                                backgroundColor: '#10b981',
                                borderRadius: 8,
                                borderSkipped: false
                            },
                            {
                                label: 'Berjalan',
                                data: monitoringChartData.inProgress,
                                backgroundColor: '#f59e0b',
                                borderRadius: 8,
                                borderSkipped: false
                            },
                            {
                                label: 'Belum mulai',
                                data: monitoringChartData.notStarted,
                                backgroundColor: '#cbd5e1',
                                borderRadius: 8,
                                borderSkipped: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: animationOption,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        weight: 600
                                    }
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grid: {
                                    color: gridLineColor
                                }
                            }
                        }
                    }
                });

                const taskCanvas = document.getElementById('taskStatusChartCanvas');
                if (taskCanvas) {
                    new Chart(taskCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: ['Todo', 'Berjalan', 'Selesai'],
                            datasets: [{
                                data: [taskChartData.todo, taskChartData.inProgress, taskChartData.done],
                                backgroundColor: ['#cbd5e1', '#6366f1', '#10b981'],
                                borderWidth: 3,
                                borderColor: 'transparent',
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '68%',
                            animation: animationOption,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            })();
        </script>
    @endif
@endsection
