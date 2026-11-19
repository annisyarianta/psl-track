<?php

namespace App\Http\Controllers;

use App\Models\IndikatorKegiatan;
use App\Models\Monitoring;
use App\Models\Periode;
use App\Models\Tahun;
use App\Models\Task;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private const MONITORING_STATUS_NOT_STARTED = 'notstarted';
    private const MONITORING_STATUS_IN_PROGRESS = 'onprogress';
    private const MONITORING_STATUS_DONE = 'done';

    private const TASK_STATUS_TODO = 'todo';
    private const TASK_STATUS_IN_PROGRESS = 'inprogress';
    private const TASK_STATUS_DONE = 'done';

    private const ROLE_MANAGER = 'manager';
    private const ROLE_ASMEN = 'asmen';
    private const ROLE_STAFF = 'staff';

    private const SCOPE_ALL = 'all';
    private const SCOPE_UNIT = 'unit';
    private const SCOPE_STAFF = 'staff';

    private const PERIOD_TYPE_ORDER_SQL = "FIELD(jenis_periode, 'bulanan', 'triwulan', 'semester', 'tahunan')";

    private const PERIOD_UNIT_LABELS = [
        'bulanan' => 'bulan',
        'triwulan' => 'triwulan',
        'semester' => 'semester',
        'tahunan' => 'tahun',
    ];

    private const ALL_PERIODS_VALUE = 'semua';

    public function index(Request $request)
    {
        $loggedInUser = Auth::user();
        $isAllPeriods = $this->isAllPeriodsRequested($request);

        [$scopeType, $scopeId, $roleLabel, $scopeDescription] = $this->resolveAccessScope($loggedInUser);

        /* ---------- Filter tahun dan periode ---------- */
        $availableYears = Tahun::where('tahun', '<=', now()->year)
            ->orderByDesc('tahun')
            ->get();

        $selectedYear = $availableYears->firstWhere('id_tahun', (int) $request->query('tahun'))
            ?? $availableYears->firstWhere('tahun', now()->year)
            ?? $availableYears->first();

        // Urutan: bulanan, triwulan, semester, tahunan; di dalam jenis diurutkan menurut urutan_periode.
        $periodsOfSelectedYear = $selectedYear
            ? Periode::where('id_tahun', $selectedYear->id_tahun)
            ->orderByRaw(self::PERIOD_TYPE_ORDER_SQL)
            ->orderBy('urutan_periode')
            ->get()
            ->values()
            : collect();

        if ($periodsOfSelectedYear->isEmpty()) {
            return view('dashboard.index', [
                'hasData' => false,
                'loggedInUser' => $loggedInUser,
                'roleLabel' => $roleLabel,
                'scopeDescription' => $scopeDescription,
                'greeting' => 'Selamat datang',
                'greetingName' => $this->buildGreetingName($loggedInUser->nama),
                'availableYears' => $availableYears,
                'selectedYear' => $selectedYear,
                'periodOptions' => collect(),
                'periodOptionGroups' => collect(),
                'isAllPeriods' => false,
                'selectedPeriod' => null,
            ]);
        }

        $periodOptions = $periodsOfSelectedYear->map(fn($period) => [
            'id' => $period->id_periode,
            'label' => $period->nama_periode,
        ]);

        $periodOptionGroups = $periodsOfSelectedYear
            ->groupBy('jenis_periode')
            ->map(fn($periodsOfType) => $periodsOfType->map(fn($period) => [
                'id' => $period->id_periode,
                'label' => $period->nama_periode,
            ])->values());

        $selectedPeriod = $isAllPeriods
            ? null
            : $this->resolveSelectedPeriod($request, $selectedYear, $periodsOfSelectedYear);
        $selectedPeriodLabel = $isAllPeriods ? 'Semua Periode' : $selectedPeriod->nama_periode;
        $allPeriodIdsOfYear = $periodsOfSelectedYear->pluck('id_periode')->all();

        $elapsedPeriods = $periodsOfSelectedYear
            ->filter(fn($period) => $this->isPeriodElapsed($period, $selectedYear))
            ->values();
        $elapsedPeriodIds = $elapsedPeriods->pluck('id_periode')->all();

        $monitoringPeriodIds = $isAllPeriods ? $elapsedPeriodIds : [$selectedPeriod->id_periode];

        /* ---------- Grafik status monitoring ---------- */
        if ($isAllPeriods) {
            $monitoringChartTitle = 'Status monitoring per jenis periode';
            $monitoringChartData = $this->buildMonitoringChartDataPerPeriodType($elapsedPeriods, $scopeType, $scopeId);

            $doneMonitoringCount = array_sum($monitoringChartData['done']);
            $inProgressMonitoringCount = array_sum($monitoringChartData['inProgress']);
            $notStartedMonitoringInSelectedPeriod = array_sum($monitoringChartData['notStarted']);
        } else {
            $chartPeriods = $periodsOfSelectedYear
                ->where('jenis_periode', $selectedPeriod->jenis_periode)
                ->values();
            $monitoringChartTitle = 'Status monitoring per ' . self::PERIOD_UNIT_LABELS[$selectedPeriod->jenis_periode];
            $monitoringChartData = $this->buildMonitoringChartDataPerPeriod($chartPeriods, $scopeType, $scopeId);

            $selectedPeriodIndex = $chartPeriods->search(
                fn($period) => (int) $period->id_periode === (int) $selectedPeriod->id_periode
            );
            $doneMonitoringCount = $monitoringChartData['done'][$selectedPeriodIndex];
            $inProgressMonitoringCount = $monitoringChartData['inProgress'][$selectedPeriodIndex];
            $notStartedMonitoringInSelectedPeriod = $monitoringChartData['notStarted'][$selectedPeriodIndex];
        }

        /* ---------- Angka periode terpilih ---------- */
        $totalMonitoringInSelectedPeriod = $doneMonitoringCount
            + $inProgressMonitoringCount
            + $notStartedMonitoringInSelectedPeriod;
        $filledMonitoringInSelectedPeriod = $totalMonitoringInSelectedPeriod - $notStartedMonitoringInSelectedPeriod;
        $fillingPercentage = $this->calculatePercentage($filledMonitoringInSelectedPeriod, $totalMonitoringInSelectedPeriod);

        $activeIndicatorCount = $this->buildMonitoringQuery($allPeriodIdsOfYear, $scopeType, $scopeId)
            ->distinct()
            ->count('id_indikator_sub_kegiatan');

        /* ---------- Task ---------- */
        $taskStatusCounts = $this->buildTaskQuery($loggedInUser)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $taskChartData = [
            'todo' => (int) ($taskStatusCounts[self::TASK_STATUS_TODO] ?? 0),
            'inProgress' => (int) ($taskStatusCounts[self::TASK_STATUS_IN_PROGRESS] ?? 0),
            'done' => (int) ($taskStatusCounts[self::TASK_STATUS_DONE] ?? 0),
        ];
        $activeTaskCount = $taskChartData['todo'] + $taskChartData['inProgress'];
        $totalTaskCount = $activeTaskCount + $taskChartData['done'];

        $overdueTaskCount = $this->buildTaskQuery($loggedInUser)
            ->where('status', '!=', self::TASK_STATUS_DONE)
            ->whereDate('tenggat_waktu', '<', today())
            ->count();

        $taskDueSoonCount = $this->buildTaskQuery($loggedInUser)
            ->where('status', '!=', self::TASK_STATUS_DONE)
            ->whereBetween('tenggat_waktu', [today(), today()->addDays(3)->endOfDay()])
            ->count();

        $fillingNoteSuffix = $isAllPeriods && $selectedYear->tahun == now()->year
            ? ' · periode yang sudah berjalan'
            : '';

        /* ---------- Kartu ringkasan ---------- */
        $summaryCards = [
            [
                'label' => $scopeType === self::SCOPE_STAFF ? 'Indikator saya' : 'Indikator aktif',
                'value' => $activeIndicatorCount,
                'note' => 'Tahun ' . $selectedYear->tahun,
                'icon' => 'mdi-bullseye-arrow',
                'tone' => 'indigo',
            ],
            [
                'label' => 'Pengisian ' . $selectedPeriodLabel,
                'value' => $fillingPercentage . '%',
                'note' => $filledMonitoringInSelectedPeriod . ' dari ' . $totalMonitoringInSelectedPeriod . ' sudah diisi' . $fillingNoteSuffix,
                'icon' => 'mdi-clipboard-check-outline',
                'tone' => 'teal',
            ],
            [
                'label' => 'Task aktif',
                'value' => $activeTaskCount,
                'note' => $taskChartData['done'] . ' task sudah selesai',
                'icon' => 'mdi-format-list-checks',
                'tone' => 'amber',
            ],
            [
                'label' => 'Task terlambat',
                'value' => $overdueTaskCount,
                'note' => $overdueTaskCount > 0 ? 'Perlu segera ditindaklanjuti' : 'Tidak ada yang terlambat',
                'icon' => 'mdi-clock-alert-outline',
                'tone' => 'rose',
            ],
        ];

        /* ---------- Blok perbandingan (beda isi per role) ---------- */
        [$rankingTitle, $rankingItems, $lowestRankingItem] = $this->buildRankingBlock(
            $loggedInUser,
            $scopeType,
            $scopeId,
            $monitoringPeriodIds
        );

        /* ---------- Perlu perhatian ---------- */
        $attentionItems = [];

        $attentionPeriodDescription = $isAllPeriods
            ? ($selectedYear->tahun < now()->year ? 'semua periode' : 'periode yang sudah berjalan')
            : $selectedPeriodLabel;
        $attentionUnitLabel = $isAllPeriods ? ' monitoring' : ' indikator';

        if ($notStartedMonitoringInSelectedPeriod > 0) {
            $attentionItems[] = [
                'icon' => 'mdi-alert-circle-outline',
                'tone' => 'warning',
                'title' => $notStartedMonitoringInSelectedPeriod . $attentionUnitLabel . ' belum diisi pada ' . $attentionPeriodDescription,
                'description' => $lowestRankingItem && $scopeType !== self::SCOPE_STAFF
                    ? 'Paling tertinggal: ' . $lowestRankingItem['label']
                    : 'Segera lengkapi sebelum periode berakhir',
            ];

            $attentionPeriodDescription = $isAllPeriods
                ? ($selectedYear->tahun < now()->year ? 'semua periode' : 'periode yang sudah berjalan')
                : $selectedPeriodLabel;
            $attentionUnitLabel = $isAllPeriods ? ' monitoring' : ' indikator';
        }

        if ($overdueTaskCount > 0) {
            $attentionItems[] = [
                'icon' => 'mdi-clock-alert-outline',
                'tone' => 'danger',
                'title' => $overdueTaskCount . ' task melewati tenggat',
                'description' => 'Buka Task Tracker untuk menindaklanjuti',
            ];
        }

        if ($taskDueSoonCount > 0) {
            $attentionItems[] = [
                'icon' => 'mdi-calendar-alert',
                'tone' => 'info',
                'title' => $taskDueSoonCount . ' task jatuh tempo dalam 3 hari',
                'description' => 'Periksa tenggat di kalender Task Tracker',
            ];
        }

        if ($scopeType === self::SCOPE_UNIT) {
            $indicatorWithoutPicStaffCount = IndikatorKegiatan::query()
                ->whereHas('kegiatan.indikatorProgram.picUnit', fn($query) => $query->where('id_unit', $scopeId))
                ->doesntHave('picStaff')
                ->count();

            if ($indicatorWithoutPicStaffCount > 0) {
                $attentionItems[] = [
                    'icon' => 'mdi-account-question-outline',
                    'tone' => 'warning',
                    'title' => $indicatorWithoutPicStaffCount . ' indikator belum punya PIC staff',
                    'description' => 'Tentukan PIC staff pada menu Kegiatan',
                ];
            }
        }

        return view('dashboard.index', [
            'hasData' => true,
            'loggedInUser' => $loggedInUser,
            'roleLabel' => $roleLabel,
            'scopeDescription' => $scopeDescription,
            'greeting' => 'Selamat datang',
            'greetingName' => $this->buildGreetingName($loggedInUser->nama),
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear,
            'periodOptions' => $periodOptions,
            'periodOptionGroups' => $periodOptionGroups,
            'selectedPeriod' => $selectedPeriod,
            'selectedPeriodLabel' => $selectedPeriodLabel,
            'summaryCards' => $summaryCards,
            'monitoringChartTitle' => $monitoringChartTitle,
            'monitoringChartData' => $monitoringChartData,
            'taskChartData' => $taskChartData,
            'totalTaskCount' => $totalTaskCount,
            'rankingTitle' => $rankingTitle,
            'rankingItems' => $rankingItems,
            'attentionItems' => $attentionItems,
            'isAllPeriods' => $isAllPeriods,
        ]);
    }

    private function resolveSelectedPeriod(Request $request, Tahun $selectedYear, Collection $periodsOfSelectedYear): Periode
    {
        $requestedPeriodId = (int) ($request->query('periode') ?? $request->query('triwulan'));
        $requestedPeriod = $requestedPeriodId > 0 ? Periode::find($requestedPeriodId) : null;

        if ($requestedPeriod) {
            $equivalentPeriod = $periodsOfSelectedYear->first(
                fn($period) => $period->jenis_periode === $requestedPeriod->jenis_periode
                    && (int) $period->urutan_periode === (int) $requestedPeriod->urutan_periode
            );

            if ($equivalentPeriod) {
                return $equivalentPeriod;
            }
        }

        $monthlyPeriods = $periodsOfSelectedYear->where('jenis_periode', 'bulanan');

        return ($selectedYear->tahun == now()->year
            ? $monthlyPeriods->firstWhere('urutan_periode', now()->month)
            : $monthlyPeriods->last())
            ?? $periodsOfSelectedYear->first();
    }

    private function isAllPeriodsRequested(Request $request): bool
    {
        return ($request->query('periode') ?? $request->query('triwulan')) === self::ALL_PERIODS_VALUE;
    }

    private function isPeriodElapsed(Periode $period, Tahun $selectedYear): bool
    {
        $currentYear = now()->year;

        if ($selectedYear->tahun < $currentYear) {
            return true;
        }

        if ($selectedYear->tahun > $currentYear) {
            return false;
        }

        return match ($period->jenis_periode) {
            'bulanan' => (int) $period->urutan_periode <= now()->month,
            'triwulan' => (int) $period->urutan_periode <= now()->quarter,
            'semester' => (int) $period->urutan_periode <= (now()->month <= 6 ? 1 : 2),
            default => false,
        };
    }


    private function buildMonitoringChartDataPerPeriod(Collection $chartPeriods, string $scopeType, $scopeId): array
    {
        $monitoringStatusRows = $this->buildMonitoringQuery($chartPeriods->pluck('id_periode')->all(), $scopeType, $scopeId)
            ->selectRaw('id_periode, COALESCE(status, ?) as status_monitoring, COUNT(*) as total', [self::MONITORING_STATUS_NOT_STARTED])
            ->groupBy('id_periode', 'status_monitoring')
            ->get();

        $monitoringChartData = ['labels' => [], 'done' => [], 'inProgress' => [], 'notStarted' => []];

        foreach ($chartPeriods as $period) {
            $countByStatus = $monitoringStatusRows
                ->where('id_periode', $period->id_periode)
                ->pluck('total', 'status_monitoring');

            $monitoringChartData['labels'][] = $this->buildChartLabel($period);
            $monitoringChartData['done'][] = (int) ($countByStatus[self::MONITORING_STATUS_DONE] ?? 0);
            $monitoringChartData['inProgress'][] = (int) ($countByStatus[self::MONITORING_STATUS_IN_PROGRESS] ?? 0);
            $monitoringChartData['notStarted'][] = (int) ($countByStatus[self::MONITORING_STATUS_NOT_STARTED] ?? 0);
        }

        return $monitoringChartData;
    }


    private function buildMonitoringChartDataPerPeriodType(Collection $elapsedPeriods, string $scopeType, $scopeId): array
    {
        $monitoringStatusRows = $this->buildMonitoringQuery($elapsedPeriods->pluck('id_periode')->all(), $scopeType, $scopeId)
            ->selectRaw('id_periode, COALESCE(status, ?) as status_monitoring, COUNT(*) as total', [self::MONITORING_STATUS_NOT_STARTED])
            ->groupBy('id_periode', 'status_monitoring')
            ->get();

        $monitoringChartData = ['labels' => [], 'done' => [], 'inProgress' => [], 'notStarted' => []];

        foreach ($elapsedPeriods->groupBy('jenis_periode') as $periodType => $periodsOfType) {
            $countByStatus = $monitoringStatusRows
                ->whereIn('id_periode', $periodsOfType->pluck('id_periode')->all())
                ->groupBy('status_monitoring')
                ->map(fn($rowsOfStatus) => (int) $rowsOfStatus->sum('total'));

            $monitoringChartData['labels'][] = ucfirst($periodType);
            $monitoringChartData['done'][] = (int) ($countByStatus[self::MONITORING_STATUS_DONE] ?? 0);
            $monitoringChartData['inProgress'][] = (int) ($countByStatus[self::MONITORING_STATUS_IN_PROGRESS] ?? 0);
            $monitoringChartData['notStarted'][] = (int) ($countByStatus[self::MONITORING_STATUS_NOT_STARTED] ?? 0);
        }

        return $monitoringChartData;
    }


    private function buildChartLabel(Periode $period): string
    {
        return match ($period->jenis_periode) {
            'bulanan' => mb_substr($period->nama_periode, 0, 3),
            'triwulan' => 'TW ' . $period->urutan_periode,
            default => $period->nama_periode,
        };
    }


    private function resolveAccessScope(User $loggedInUser): array
    {
        return match ($loggedInUser->role) {
            self::ROLE_ASMEN => [
                self::SCOPE_UNIT,
                $loggedInUser->id_unit,
                'Asisten Manager',
                'Unit ' . (Unit::find($loggedInUser->id_unit)->nama_unit ?? '-'),
            ],
            self::ROLE_STAFF => [
                self::SCOPE_STAFF,
                $loggedInUser->getKey(),
                'Staff',
                'milik Anda',
            ],
            default => [
                self::SCOPE_ALL,
                null,
                $loggedInUser->role === self::ROLE_MANAGER ? 'Manager' : ucfirst((string) $loggedInUser->role),
                'seluruh unit',
            ],
        };
    }

    private function buildMonitoringQuery(array $periodIds, string $scopeType, $scopeId): Builder
    {
        $monitoringQuery = Monitoring::query()->whereIn('id_periode', $periodIds);

        if ($scopeType === self::SCOPE_UNIT) {
            $monitoringQuery->whereHas(
                'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram.picUnit',
                fn($query) => $query->where('id_unit', $scopeId)
            );
        } elseif ($scopeType === self::SCOPE_STAFF) {
            $monitoringQuery->whereHas(
                'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.picStaff',
                fn($query) => $query->where('id_user', $scopeId)
            );
        }

        return $monitoringQuery;
    }

    private function buildTaskQuery(User $loggedInUser): Builder
    {
        $taskQuery = Task::query();

        if ($loggedInUser->role === self::ROLE_ASMEN || $loggedInUser->role === self::ROLE_STAFF) {
            $taskQuery->milikUser((int) $loggedInUser->getKey());
        }

        return $taskQuery;
    }

    private function buildRankingBlock(User $loggedInUser, string $scopeType, $scopeId, array $periodIds): array
    {
        if ($scopeType === self::SCOPE_STAFF) {
            $ownMonitoringRows = $this->buildMonitoringQuery($periodIds, self::SCOPE_STAFF, $scopeId)->with('indikatorSubKegiatan')
                ->get();

            if (count($periodIds) > 1) {
                $items = $ownMonitoringRows
                    ->groupBy('id_indikator_sub_kegiatan')
                    ->map(function ($monitoringRowsOfIndicator) {
                        $totalPeriods = $monitoringRowsOfIndicator->count();
                        $filledPeriods = $monitoringRowsOfIndicator
                            ->filter(fn($monitoring) => ($monitoring->status ?? self::MONITORING_STATUS_NOT_STARTED) !== self::MONITORING_STATUS_NOT_STARTED)
                            ->count();

                        return [
                            'label' => $monitoringRowsOfIndicator->first()->indikatorSubKegiatan->nama_indikator_sub_kegiatan ?? '-',
                            'percent' => $this->calculatePercentage($filledPeriods, $totalPeriods),
                            'detail' => $filledPeriods . ' dari ' . $totalPeriods . ' periode diisi',
                        ];
                    })->values()->all();
            } else {
                $items = $ownMonitoringRows->map(function ($monitoring) {
                    $status = $monitoring->status ?? self::MONITORING_STATUS_NOT_STARTED;

                    return [
                        'label' => $monitoring->indikatorSubKegiatan->nama_indikator_sub_kegiatan ?? '-',
                        'percent' => match ($status) {
                            self::MONITORING_STATUS_DONE => 100,
                            self::MONITORING_STATUS_IN_PROGRESS => 50,
                            default => 0,
                        },
                        'detail' => match ($status) {
                            self::MONITORING_STATUS_DONE => 'Selesai',
                            self::MONITORING_STATUS_IN_PROGRESS => 'Berjalan',
                            default => 'Belum diisi',
                        },
                    ];
                })->values()->all();
            }

            return ['Indikator saya', $items, null];
        }

        if ($scopeType === self::SCOPE_UNIT) {
            $title = 'Pengisian monitoring per staff';
            $subjects = User::where('role', self::ROLE_STAFF)
                ->where('id_unit', $scopeId)
                ->get()
                ->map(fn($staff) => ['label' => $staff->nama, 'scopeType' => self::SCOPE_STAFF, 'scopeId' => $staff->getKey()]);
        } else {
            $title = 'Pengisian monitoring per unit';
            $subjects = Unit::orderBy('id_unit')
                ->get()
                ->map(fn($unit) => ['label' => $unit->nama_unit, 'scopeType' => self::SCOPE_UNIT, 'scopeId' => $unit->id_unit]);
        }

        $items = $subjects->map(function ($subject) use ($periodIds) {
            $countByStatus = $this->buildMonitoringQuery($periodIds, $subject['scopeType'], $subject['scopeId'])
                ->selectRaw('COALESCE(status, ?) as status_monitoring, COUNT(*) as total', [self::MONITORING_STATUS_NOT_STARTED])
                ->groupBy('status_monitoring')
                ->pluck('total', 'status_monitoring');

            $total = (int) $countByStatus->sum();
            $filled = $total - (int) ($countByStatus[self::MONITORING_STATUS_NOT_STARTED] ?? 0);

            return [
                'label' => $subject['label'],
                'percent' => $this->calculatePercentage($filled, $total),
                'detail' => $filled . ' dari ' . $total . ' indikator',
                'total' => $total,
            ];
        })->filter(fn($item) => $item['total'] > 0)
            ->sortByDesc('percent')
            ->values();

        return [$title, $items->all(), $items->last()];
    }

    private function calculatePercentage(int $part, int $total): int
    {
        return $total > 0 ? (int) round($part / $total * 100) : 0;
    }

    private function buildGreetingName(?string $fullName): string
    {
        $nameWithoutBackTitle = trim(explode(',', (string) $fullName)[0]);
        $nameWords = preg_split('/\s+/', $nameWithoutBackTitle, -1, PREG_SPLIT_NO_EMPTY);

        return implode(' ', array_slice($nameWords, 0, 2)) ?: $nameWithoutBackTitle;
    }
}
