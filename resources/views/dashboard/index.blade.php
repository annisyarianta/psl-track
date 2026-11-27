@extends('layouts.app')

@section('title')
    Dashboard | PSL Track
@endsection

@section('content')
    @php
        $selectedPeriodWithYear = $hasData
            ? ($isAllPeriods
                ? 'semua periode tahun ' . $selectedYear->tahun
                : ($selectedPeriod->jenis_periode === 'tahunan'
                    ? 'periode tahunan ' . $selectedYear->tahun
                    : $selectedPeriodLabel . ' tahun ' . $selectedYear->tahun))
            : null;
    @endphp
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

        .psl-dash-filter-field select option,
        .psl-dash-filter-field select optgroup {
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

        /* ---------- Status task per orang ---------- */
        .psl-dash-person-section {
            margin-bottom: 20px;
        }

        .psl-dash-person-head {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .psl-dash-person-search {
            width: 220px;
            max-width: 100%;
        }

        .psl-dash-person-scroll {
            overflow-x: auto;
        }

        .psl-dash-person-table {
            width: 100%;
            min-width: 480px;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .psl-dash-person-table th {
            padding: 10px 6px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            color: var(--bs-secondary-color, #6b7280);
        }

        .psl-dash-person-table td {
            padding: 11px 6px;
            text-align: center;
            vertical-align: middle;
            border-top: 1px solid var(--psl-dash-track);
        }

        .psl-dash-person-table .psl-dash-person-name-cell {
            width: 34%;
            text-align: left;
        }

        .psl-dash-person-table .psl-dash-person-total-cell {
            width: 12%;
        }

        .psl-dash-person-identity {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .psl-dash-person-avatar {
            flex: 0 0 34px;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #4338ca;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.16);
        }

        .psl-dash-person-identity-text {
            min-width: 0;
        }

        .psl-dash-person-name {
            margin: 0;
            overflow: hidden;
            font-size: 14px;
            font-weight: 600;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .psl-dash-person-self {
            font-weight: 500;
            color: var(--bs-secondary-color, #6b7280);
        }

        .psl-dash-person-subtitle {
            margin: 0;
            font-size: 12px;
            color: var(--bs-secondary-color, #6b7280);
        }

        .psl-dash-person-pill {
            display: inline-block;
            min-width: 34px;
            padding: 3px 10px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 999px;
        }

        .psl-dash-person-pill-todo {
            color: #475569;
            background: rgba(100, 116, 139, 0.16);
        }

        .psl-dash-person-pill-progress {
            color: #4338ca;
            background: rgba(99, 102, 241, 0.16);
        }

        .psl-dash-person-pill-revision {
            color: #be123c;
            background: rgba(225, 29, 72, 0.15);
        }

        .psl-dash-person-pill-done {
            color: #047857;
            background: rgba(16, 185, 129, 0.16);
        }

        .psl-dash-person-zero {
            color: var(--bs-secondary-color, #6b7280);
            opacity: 0.6;
        }

        .psl-dash-person-total {
            font-size: 14px;
            font-weight: 800;
        }

        .psl-dash-person-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 12px;
            padding-top: 12px;
            font-size: 13px;
            color: var(--bs-secondary-color, #6b7280);
            border-top: 1px solid var(--psl-dash-track);
        }

        .psl-dash-person-tiles {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .psl-dash-person-tile {
            padding: 14px 16px;
            border: 1.5px solid transparent;
            border-radius: 16px;
        }

        .psl-dash-person-tile-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--bs-secondary-color, #6b7280);
        }

        .psl-dash-person-tile-value {
            margin: 4px 0 0;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
        }

        .psl-dash-person-tile-value-zero {
            opacity: 0.45;
        }

        .psl-dash-person-tile-todo {
            background: linear-gradient(135deg, rgba(148, 163, 184, 0.22), rgba(148, 163, 184, 0.05));
            border-color: rgba(148, 163, 184, 0.5);
        }

        .psl-dash-person-tile-todo .psl-dash-person-tile-value {
            color: #475569;
        }

        .psl-dash-person-tile-progress {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.22), rgba(99, 102, 241, 0.05));
            border-color: rgba(99, 102, 241, 0.5);
        }

        .psl-dash-person-tile-progress .psl-dash-person-tile-value {
            color: #4338ca;
        }

        .psl-dash-person-tile-revision {
            background: linear-gradient(135deg, rgba(225, 29, 72, 0.22), rgba(225, 29, 72, 0.05));
            border-color: rgba(225, 29, 72, 0.5);
        }

        .psl-dash-person-tile-revision .psl-dash-person-tile-value {
            color: #be123c;
        }

        .psl-dash-person-tile-done {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.22), rgba(16, 185, 129, 0.05));
            border-color: rgba(16, 185, 129, 0.5);
        }

        .psl-dash-person-tile-done .psl-dash-person-tile-value {
            color: #047857;
        }

        @media (max-width: 767.98px) {
            .psl-dash-person-tiles {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .psl-dash-person-search {
                width: 100%;
            }

            .psl-dash-person-table .psl-dash-person-total-cell {
                display: none;
            }

            .psl-dash-person-table .psl-dash-person-name-cell {
                width: 36%;
            }
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
                                    Berikut ringkasan capaian KPI {{ $scopeDescription }} untuk
                                    {{ $selectedPeriodWithYear }}.
                                @else
                                    Data KPI {{ $scopeDescription }} akan tampil di sini setelah periode tersedia.
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
                                        Periode
                                        <select name="periode" class="form-select">
                                            <option value="semua" class="fw-bold" @selected($isAllPeriods)>Semua
                                                periode</option>
                                            @foreach ($periodOptionGroups as $periodType => $periodOptionsOfType)
                                                <optgroup label="{{ ucfirst($periodType) }}">
                                                    @foreach ($periodOptionsOfType as $periodOption)
                                                        <option value="{{ $periodOption['id'] }}"
                                                            @selected(!$isAllPeriods && $periodOption['id'] === $selectedPeriod->id_periode)>
                                                            {{ $periodOption['label'] }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
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
                                    <h2 class="psl-dash-card-title">{{ $monitoringChartTitle }}</h2>
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
                                            aria-label="Diagram batang {{ mb_strtolower($monitoringChartTitle) }}"></canvas>
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

                        {{-- ===================== STATUS TASK PER ORANG ===================== --}}
                        @php
                            $taskStatusColumns = [
                                [
                                    'key' => 'todo',
                                    'label' => 'To Do',
                                    'color' => '#94a3b8',
                                    'pillClass' => 'psl-dash-person-pill-todo',
                                    'tileClass' => 'psl-dash-person-tile-todo',
                                ],
                                [
                                    'key' => 'inProgress',
                                    'label' => 'In Progress',
                                    'color' => '#6366f1',
                                    'pillClass' => 'psl-dash-person-pill-progress',
                                    'tileClass' => 'psl-dash-person-tile-progress',
                                ],
                                [
                                    'key' => 'revisi',
                                    'label' => 'Revisi',
                                    'color' => '#e11d48',
                                    'pillClass' => 'psl-dash-person-pill-revision',
                                    'tileClass' => 'psl-dash-person-tile-revision',
                                ],
                                [
                                    'key' => 'done',
                                    'label' => 'Done',
                                    'color' => '#10b981',
                                    'pillClass' => 'psl-dash-person-pill-done',
                                    'tileClass' => 'psl-dash-person-tile-done',
                                ],
                            ];
                            $personCount = count($taskStatusPerPersonRows);
                            $hasPersonControls =
                                !$isOwnTaskStatusOnly && $personCount > $taskStatusPerPersonVisibleRows;
                        @endphp
                        <section class="psl-dash-person-section">
                            <div class="card psl-dash-card">
                                <div class="psl-dash-card-body">
                                    <div class="psl-dash-person-head">
                                        <div>
                                            <h2 class="psl-dash-card-title">{{ $taskStatusPerPersonTitle }}</h2>
                                            <p class="psl-dash-card-caption mb-0">Jumlah task yang diterima, dikelompokkan
                                                menurut status.</p>
                                        </div>
                                        @if ($hasPersonControls)
                                            <input type="search" id="taskPersonSearchInput"
                                                class="form-control psl-dash-person-search"
                                                placeholder="Cari nama atau unit" aria-label="Cari nama atau unit">
                                        @endif
                                    </div>

                                    @if ($personCount === 0)
                                        <div class="psl-dash-empty psl-dash-empty-muted">
                                            <i class="mdi mdi-account-group-outline"></i>
                                            <strong>Belum ada data</strong>
                                            <span>Data muncul setelah ada pengguna di cakupan Anda.</span>
                                        </div>
                                    @elseif ($isOwnTaskStatusOnly)
                                        @php $ownTaskStatus = $taskStatusPerPersonRows[0]; @endphp
                                        <div class="psl-dash-person-tiles">
                                            @foreach ($taskStatusColumns as $column)
                                                @php $statusCount = $ownTaskStatus['counts'][$column['key']]; @endphp
                                                <div class="psl-dash-person-tile {{ $column['tileClass'] }}">
                                                    <div class="psl-dash-person-tile-label">
                                                        <i class="psl-dash-legend-dot"
                                                            style="background: {{ $column['color'] }}"></i>{{ $column['label'] }}
                                                    </div>
                                                    <p
                                                        class="psl-dash-person-tile-value {{ $statusCount === 0 ? 'psl-dash-person-tile-value-zero' : '' }}">
                                                        {{ $statusCount }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="psl-dash-ranking-detail mt-3 mb-0">Total {{ $ownTaskStatus['total'] }}
                                            task</p>
                                    @else
                                        <div class="psl-dash-person-scroll">
                                            <table class="psl-dash-person-table">
                                                <thead>
                                                    <tr>
                                                        <th class="psl-dash-person-name-cell">Nama</th>
                                                        @foreach ($taskStatusColumns as $column)
                                                            <th>
                                                                <i class="psl-dash-legend-dot"
                                                                    style="background: {{ $column['color'] }}"></i>{{ $column['label'] }}
                                                            </th>
                                                        @endforeach
                                                        <th class="psl-dash-person-total-cell">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($taskStatusPerPersonRows as $personRow)
                                                        <tr class="psl-dash-person-row"
                                                            data-search="{{ mb_strtolower($personRow['name'] . ' ' . $personRow['subtitle']) }}"
                                                            @if ($hasPersonControls && $loop->index >= $taskStatusPerPersonVisibleRows) hidden @endif>
                                                            <td class="psl-dash-person-name-cell">
                                                                <div class="psl-dash-person-identity">
                                                                    <span
                                                                        class="psl-dash-person-avatar">{{ $personRow['initials'] }}</span>
                                                                    <div class="psl-dash-person-identity-text">
                                                                        <p class="psl-dash-person-name">
                                                                            {{ $personRow['name'] }}
                                                                            @if ($personRow['isLoggedInUser'])
                                                                                <span
                                                                                    class="psl-dash-person-self">(Anda)</span>
                                                                            @endif
                                                                        </p>
                                                                        <p class="psl-dash-person-subtitle">
                                                                            {{ $personRow['subtitle'] }}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            @foreach ($taskStatusColumns as $column)
                                                                @php $statusCount = $personRow['counts'][$column['key']]; @endphp
                                                                <td>
                                                                    @if ($statusCount > 0)
                                                                        <span
                                                                            class="psl-dash-person-pill {{ $column['pillClass'] }}">{{ $statusCount }}</span>
                                                                    @else
                                                                        <span class="psl-dash-person-zero">-</span>
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                            <td class="psl-dash-person-total-cell psl-dash-person-total">
                                                                {{ $personRow['total'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <p id="taskPersonEmptyMessage" class="psl-dash-ranking-detail mt-3 mb-0" hidden>
                                            Tidak ada yang cocok dengan pencarian.</p>

                                        <div class="psl-dash-person-foot">
                                            <span id="taskPersonCountText">Menampilkan
                                                {{ min($personCount, $taskStatusPerPersonVisibleRows) }} dari
                                                {{ $personCount }} orang</span>
                                            @if ($hasPersonControls)
                                                <button type="button" id="taskPersonToggleButton"
                                                    class="btn btn-link p-0 fw-semibold">Lihat semua</button>
                                            @endif
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
                                    <p class="psl-dash-card-caption">Kondisi {{ $selectedPeriodWithYear }}.</p>

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
                                <strong>Periode belum tersedia</strong>
                                <span>Tambahkan tahun terlebih dahulu. Periode dibuat otomatis bersama tahun.</span>
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
                                borderSkipped: false,
                                maxBarThickness: 72
                            },
                            {
                                label: 'Berjalan',
                                data: monitoringChartData.inProgress,
                                backgroundColor: '#f59e0b',
                                borderRadius: 8,
                                borderSkipped: false,
                                maxBarThickness: 72
                            },
                            {
                                label: 'Belum mulai',
                                data: monitoringChartData.notStarted,
                                backgroundColor: '#cbd5e1',
                                borderRadius: 8,
                                borderSkipped: false,
                                maxBarThickness: 72
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

        <script>
            (function() {
                const personRows = Array.from(document.querySelectorAll('.psl-dash-person-row'));
                const searchInput = document.getElementById('taskPersonSearchInput');
                const toggleButton = document.getElementById('taskPersonToggleButton');
                const countText = document.getElementById('taskPersonCountText');
                const emptySearchMessage = document.getElementById('taskPersonEmptyMessage');

                // Tanpa tombol dan pencarian (orangnya sedikit), semua baris sudah tampil.
                if (personRows.length === 0 || !searchInput || !toggleButton) {
                    return;
                }

                const visibleRowLimit = {{ $taskStatusPerPersonVisibleRows }};
                let isShowingAllRows = false;

                function updateVisibleRows() {
                    const keyword = searchInput.value.trim().toLowerCase();
                    const isSearching = keyword !== '';
                    let matchedCount = 0;
                    let shownCount = 0;

                    personRows.forEach(function(personRow) {
                        const isMatched = !isSearching || personRow.dataset.search.includes(keyword);
                        const canShowMoreRows = isShowingAllRows || isSearching || shownCount < visibleRowLimit;
                        const shouldShow = isMatched && canShowMoreRows;

                        if (isMatched) {
                            matchedCount++;
                        }

                        if (shouldShow) {
                            shownCount++;
                        }

                        personRow.hidden = !shouldShow;
                    });

                    countText.textContent = 'Menampilkan ' + shownCount + ' dari ' + matchedCount + ' orang';
                    emptySearchMessage.hidden = matchedCount !== 0;
                    toggleButton.hidden = isSearching;
                    toggleButton.textContent = isShowingAllRows ? 'Tampilkan lebih sedikit' : 'Lihat semua';
                }

                searchInput.addEventListener('input', updateVisibleRows);

                toggleButton.addEventListener('click', function() {
                    isShowingAllRows = !isShowingAllRows;
                    updateVisibleRows();
                });
            })();
        </script>
    @endif
@endsection
