<?php

namespace App\Http\Controllers;

use App\Models\IndikatorKegiatan;
use App\Models\Monitoring;
use App\Models\PeriodeTw;
use App\Models\Tahun;
use App\Models\Task;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | NILAI YANG MUNGKIN PERLU DISESUAIKAN DENGAN DATABASE ANDA
    |--------------------------------------------------------------------------
    */
    private const MONITORING_STATUS_NOT_STARTED = 'notstarted';
    private const MONITORING_STATUS_IN_PROGRESS = 'inprogress';
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

    public function index(Request $request)
    {
        $loggedInUser = Auth::user();

        [$scopeType, $scopeId, $roleLabel, $scopeDescription] = $this->resolveAccessScope($loggedInUser);

        /* ---------- Filter tahun dan triwulan ---------- */
        $availableYears = Tahun::where('tahun', '<=', now()->year)
            ->orderByDesc('tahun')
            ->get();

        $selectedYear = $availableYears->firstWhere('id_tahun', (int) $request->query('tahun'))
            ?? $availableYears->firstWhere('tahun', now()->year)
            ?? $availableYears->first();

        $periodsOfSelectedYear = $selectedYear
            ? PeriodeTw::where('id_tahun', $selectedYear->id_tahun)->orderBy('id_periode_tw')->get()->values()
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
                'selectedPeriod' => null,
            ]);
        }

        $periodOptions = $periodsOfSelectedYear->map(fn($period, $index) => [
            'id' => $period->id_periode_tw,
            'label' => 'TW ' . ($index + 1),
        ]);

        $defaultPeriodIndex = ($selectedYear->tahun == now()->year)
            ? min(now()->quarter - 1, $periodsOfSelectedYear->count() - 1)
            : $periodsOfSelectedYear->count() - 1;

        $selectedPeriod = $periodsOfSelectedYear->firstWhere('id_periode_tw', (int) $request->query('triwulan'))
            ?? $periodsOfSelectedYear->get($defaultPeriodIndex);

        $selectedPeriodLabel = $periodOptions->firstWhere('id', $selectedPeriod->id_periode_tw)['label'];
        $allPeriodIdsOfYear = $periodsOfSelectedYear->pluck('id_periode_tw')->all();

        /* ---------- Grafik status monitoring per triwulan ---------- */
        $monitoringStatusRows = $this->buildMonitoringQuery($allPeriodIdsOfYear, $scopeType, $scopeId)
            ->selectRaw('id_periode_tw, COALESCE(status, ?) as status_monitoring, COUNT(*) as total', [self::MONITORING_STATUS_NOT_STARTED])
            ->groupBy('id_periode_tw', 'status_monitoring')
            ->get();

        $monitoringChartData = ['labels' => [], 'done' => [], 'inProgress' => [], 'notStarted' => []];

        foreach ($periodsOfSelectedYear as $index => $period) {
            $countByStatus = $monitoringStatusRows
                ->where('id_periode_tw', $period->id_periode_tw)
                ->pluck('total', 'status_monitoring');

            $monitoringChartData['labels'][] = 'TW ' . ($index + 1);
            $monitoringChartData['done'][] = (int) ($countByStatus[self::MONITORING_STATUS_DONE] ?? 0);
            $monitoringChartData['inProgress'][] = (int) ($countByStatus[self::MONITORING_STATUS_IN_PROGRESS] ?? 0);
            $monitoringChartData['notStarted'][] = (int) ($countByStatus[self::MONITORING_STATUS_NOT_STARTED] ?? 0);
        }

        /* ---------- Angka triwulan terpilih ---------- */
        $selectedPeriodIndex = $periodsOfSelectedYear->search(
            fn($period) => $period->id_periode_tw === $selectedPeriod->id_periode_tw
        );
        $totalMonitoringInSelectedPeriod = $monitoringChartData['done'][$selectedPeriodIndex]
            + $monitoringChartData['inProgress'][$selectedPeriodIndex]
            + $monitoringChartData['notStarted'][$selectedPeriodIndex];
        $filledMonitoringInSelectedPeriod = $totalMonitoringInSelectedPeriod
            - $monitoringChartData['notStarted'][$selectedPeriodIndex];
        $notStartedMonitoringInSelectedPeriod = $monitoringChartData['notStarted'][$selectedPeriodIndex];
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
                'note' => $filledMonitoringInSelectedPeriod . ' dari ' . $totalMonitoringInSelectedPeriod . ' sudah diisi',
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
            $selectedPeriod->id_periode_tw
        );

        /* ---------- Perlu perhatian ---------- */
        $attentionItems = [];

        if ($notStartedMonitoringInSelectedPeriod > 0) {
            $attentionItems[] = [
                'icon' => 'mdi-alert-circle-outline',
                'tone' => 'warning',
                'title' => $notStartedMonitoringInSelectedPeriod . ' indikator belum diisi pada ' . $selectedPeriodLabel,
                'description' => $lowestRankingItem && $scopeType !== self::SCOPE_STAFF
                    ? 'Paling tertinggal: ' . $lowestRankingItem['label']
                    : 'Segera lengkapi sebelum triwulan berakhir',
            ];
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

        $monitoringWithoutReportFileCount = $this->buildMonitoringQuery([$selectedPeriod->id_periode_tw], $scopeType, $scopeId)
            ->whereNotNull('status')
            ->where('status', '!=', self::MONITORING_STATUS_NOT_STARTED)
            ->doesntHave('files')
            ->count();

        if ($monitoringWithoutReportFileCount > 0) {
            $attentionItems[] = [
                'icon' => 'mdi-file-alert-outline',
                'tone' => 'info',
                'title' => $monitoringWithoutReportFileCount . ' monitoring belum ada file pelaporan',
                'description' => 'Unggah bukti pelaporan pada halaman Monitoring',
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
            'selectedPeriod' => $selectedPeriod,
            'selectedPeriodLabel' => $selectedPeriodLabel,
            'summaryCards' => $summaryCards,
            'monitoringChartData' => $monitoringChartData,
            'taskChartData' => $taskChartData,
            'totalTaskCount' => $totalTaskCount,
            'rankingTitle' => $rankingTitle,
            'rankingItems' => $rankingItems,
            'attentionItems' => $attentionItems,
        ]);
    }

    /**
     * Menentukan cakupan data berdasarkan role user yang login.
     * Role di luar manager/asmen/staff (misalnya direksi) sementara melihat semua data.
     */
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

    /**
     * Query dasar monitoring yang sudah dibatasi sesuai cakupan akses.
     * - all   : semua monitoring
     * - unit  : monitoring dari indikator program yang di-assign ke unit (tabel pic_unit)
     * - staff : monitoring dari indikator kegiatan yang PIC-nya staff tersebut (tabel pic_staff)
     */
    private function buildMonitoringQuery(array $periodIds, string $scopeType, $scopeId): Builder
    {
        $monitoringQuery = Monitoring::query()->whereIn('id_periode_tw', $periodIds);

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

    /**
     * Task mengikuti aturan menu Task Tracker:
     * manager melihat semua; asmen dan staff hanya task yang diberikan atau diterimanya.
     */
    private function buildTaskQuery(User $loggedInUser): Builder
    {
        $taskQuery = Task::query();

        if ($loggedInUser->role === self::ROLE_ASMEN || $loggedInUser->role === self::ROLE_STAFF) {
            $taskQuery->milikUser((int) $loggedInUser->getKey());
        }

        return $taskQuery;
    }

    /**
     * Blok kiri bawah: manager = per unit, asmen = per staff, staff = daftar indikator sendiri.
     * Mengembalikan [judul, daftar item, item dengan persentase terendah].
     */
    private function buildRankingBlock(User $loggedInUser, string $scopeType, $scopeId, int $periodId): array
    {
        if ($scopeType === self::SCOPE_STAFF) {
            $ownMonitoringRows = $this->buildMonitoringQuery([$periodId], self::SCOPE_STAFF, $scopeId)
                ->with('indikatorSubKegiatan')
                ->get();

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

        $items = $subjects->map(function ($subject) use ($periodId) {
            $countByStatus = $this->buildMonitoringQuery([$periodId], $subject['scopeType'], $subject['scopeId'])
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
