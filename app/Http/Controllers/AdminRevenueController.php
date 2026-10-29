<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminRevenueController extends Controller
{
    /**
     * Display the revenue and finance dashboard.
     */
    public function index(Request $request): View
    {
        $period = $request->query('period', '7_days');
        $startDateInput = $request->query('start_date');
        $endDateInput = $request->query('end_date');
        $paymentMethodFilter = $request->query('payment_method');
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'date');
        $sortDir = $request->query('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';

        // Resolve date range for trend chart and filtered calculations
        [$startDate, $endDate] = $this->resolveDateRange($period, $startDateInput, $endDateInput);

        // Base query for all verified paid orders
        $paidOrdersQuery = Order::paid();

        // 1. KPI Summary Cards
        $totalAllTimeRevenue = (float) Order::paid()->sum('total_price');
        $revenueToday = (float) Order::paid()->whereDate('created_at', Carbon::today())->sum('total_price');
        $revenueThisMonth = (float) Order::paid()
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');

        // Filtered metrics query
        $filteredBaseQuery = Order::paid()
            ->when($startDate && $endDate, function (Builder $query) use ($startDate, $endDate): void {
                $query->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);
            })
            ->when($paymentMethodFilter && $paymentMethodFilter !== 'all', function (Builder $query) use ($paymentMethodFilter): void {
                $query->where('payment_method', $paymentMethodFilter);
            })
            ->when($search, function (Builder $query, string $search): void {
                $query->where(function (Builder $sub) use ($search): void {
                    $sub->where('order_code', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('user', function (Builder $uq) use ($search): void {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            });

        $filteredRevenue = (float) (clone $filteredBaseQuery)->sum('total_price');
        $filteredTransactionCount = (int) (clone $filteredBaseQuery)->count();
        $averageOrderValue = $filteredTransactionCount > 0 ? (float) ($filteredRevenue / $filteredTransactionCount) : 0.0;

        // 2. Daily Revenue Trend Data (for Line Chart)
        $chartData = $this->buildDailyChartData($startDate, $endDate, $paymentMethodFilter);

        // 3. Monthly Comparison Data for current year (for Bar Chart)
        $monthlyChartData = $this->buildMonthlyChartData();

        // 4. Payment Method Distribution Breakdown
        $paymentBreakdown = $this->buildPaymentBreakdown($startDate, $endDate);

        // 5. Paginated Transaction List for Table
        $sortColumn = $sortBy === 'amount' ? 'total_price' : 'created_at';
        $transactions = (clone $filteredBaseQuery)
            ->with(['user', 'items.product'])
            ->orderBy($sortColumn, $sortDir)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Available payment methods for filter dropdown
        $availablePaymentMethods = Order::paid()
            ->whereNotNull('payment_method')
            ->distinct()
            ->pluck('payment_method');

        $lastUpdated = Carbon::now()->translatedFormat('d M Y, H:i').' WIB';

        return view('admin.revenue.index', compact(
            'totalAllTimeRevenue',
            'revenueToday',
            'revenueThisMonth',
            'filteredRevenue',
            'filteredTransactionCount',
            'averageOrderValue',
            'chartData',
            'monthlyChartData',
            'paymentBreakdown',
            'transactions',
            'availablePaymentMethods',
            'period',
            'startDateInput',
            'endDateInput',
            'paymentMethodFilter',
            'search',
            'sortBy',
            'sortDir',
            'lastUpdated'
        ));
    }

    /**
     * Export filtered revenue transactions to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $period = $request->query('period', 'all');
        $startDateInput = $request->query('start_date');
        $endDateInput = $request->query('end_date');
        $paymentMethodFilter = $request->query('payment_method');
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'date');
        $sortDir = $request->query('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';

        [$startDate, $endDate] = $this->resolveDateRange($period, $startDateInput, $endDateInput);

        $sortColumn = $sortBy === 'amount' ? 'total_price' : 'created_at';

        $query = Order::paid()
            ->when($startDate && $endDate, function (Builder $query) use ($startDate, $endDate): void {
                $query->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);
            })
            ->when($paymentMethodFilter && $paymentMethodFilter !== 'all', function (Builder $query) use ($paymentMethodFilter): void {
                $query->where('payment_method', $paymentMethodFilter);
            })
            ->when($search, function (Builder $query, string $search): void {
                $query->where(function (Builder $sub) use ($search): void {
                    $sub->where('order_code', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('user', function (Builder $uq) use ($search): void {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->with('user')
            ->orderBy($sortColumn, $sortDir)
            ->orderBy('id', 'desc');

        $filename = 'laporan-omset-bladestore-'.Carbon::now()->format('Ymd-His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query): void {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for proper Excel Indonesian character display
            fwrite($handle, "\xEF\xBB\xBF");

            // CSV Header
            fputcsv($handle, [
                'No',
                'Tanggal Transaksi',
                'Order ID',
                'Nama Pelanggan',
                'Email Pelanggan',
                'No. Telepon',
                'Metode Pembayaran',
                'Status Pesanan',
                'Total Pembayaran (IDR)',
            ]);

            $index = 1;
            $query->chunk(100, function ($orders) use ($handle, &$index): void {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $index++,
                        $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : '-',
                        $order->order_code,
                        $order->user->name ?? 'Guest/User Terhapus',
                        $order->user->email ?? '-',
                        $order->customer_phone ?? '-',
                        $order->payment_method ?? 'Tidak Diketahui',
                        $order->status_label,
                        (float) $order->total_price,
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Resolve start and end dates based on period string or custom inputs.
     *
     * @return array{0: ?Carbon, 1: ?Carbon}
     */
    private function resolveDateRange(string $period, ?string $startInput, ?string $endInput): array
    {
        $now = Carbon::now();

        if ($period === 'custom' && $startInput && $endInput) {
            try {
                $start = Carbon::parse($startInput)->startOfDay();
                $end = Carbon::parse($endInput)->endOfDay();

                return [$start, $end];
            } catch (\Throwable) {
                // fallback to 7 days if invalid
            }
        }

        return match ($period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            '7_days' => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
            '30_days' => [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()],
            'this_month' => [$now->copy()->startOfMonth(), $now->copy()->endOfDay()],
            'this_year' => [$now->copy()->startOfYear(), $now->copy()->endOfDay()],
            'all' => [null, null],
            default => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
        };
    }

    /**
     * Build daily revenue chart data within date range.
     *
     * @return array{labels: list<string>, data: list<float>, dates: list<string>}
     */
    private function buildDailyChartData(?Carbon $start, ?Carbon $end, ?string $paymentMethod = null): array
    {
        if (! $start || ! $end) {
            $start = Carbon::now()->subDays(6)->startOfDay();
            $end = Carbon::now()->endOfDay();
        }

        $orders = Order::paid()
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->when($paymentMethod && $paymentMethod !== 'all', function (Builder $query) use ($paymentMethod): void {
                $query->where('payment_method', $paymentMethod);
            })
            ->get(['created_at', 'total_price']);

        $dailyTotals = $orders->groupBy(fn (Order $order): string => $order->created_at ? $order->created_at->format('Y-m-d') : '')
            ->map(fn ($group): float => (float) $group->sum('total_price'));

        $labels = [];
        $data = [];
        $dates = [];

        $period = CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->translatedFormat('d M');
            $dates[] = $dateString;
            $data[] = (float) ($dailyTotals->get($dateString, 0));
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'dates' => $dates,
        ];
    }

    /**
     * Build monthly revenue chart data for current calendar year (Jan-Dec).
     *
     * @return array{labels: list<string>, data: list<float>, year: int}
     */
    private function buildMonthlyChartData(): array
    {
        $year = Carbon::now()->year;

        $orders = Order::paid()
            ->whereYear('created_at', $year)
            ->get(['created_at', 'total_price']);

        $monthlyTotals = $orders->groupBy(fn (Order $order): int => $order->created_at ? (int) $order->created_at->format('n') : 0)
            ->map(fn ($group): float => (float) $group->sum('total_price'));

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $data[] = (float) ($monthlyTotals->get($m, 0));
        }

        return [
            'labels' => $monthNames,
            'data' => $data,
            'year' => $year,
        ];
    }

    /**
     * Build payment method distribution summary.
     *
     * @return list<array{name: string, count: int, total: float, percentage: float, icon: string, color: string}>
     */
    private function buildPaymentBreakdown(?Carbon $start, ?Carbon $end): array
    {
        $base = Order::paid()
            ->when($start && $end, function (Builder $query) use ($start, $end): void {
                $query->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()]);
            });

        $totalRevenue = (float) (clone $base)->sum('total_price');

        $orders = (clone $base)->get(['payment_method', 'total_price']);

        $rawMethods = $orders->groupBy(fn (Order $order): string => $order->payment_method ?: 'Lainnya')
            ->map(fn ($group, string $method): array => [
                'method_name' => $method,
                'count' => $group->count(),
                'total' => (float) $group->sum('total_price'),
            ]);

        $result = [];
        $palette = [
            '#0866FF', // Blue
            '#10B981', // Green
            '#F59E0B', // Amber
            '#8B5CF6', // Purple
            '#EC4899', // Pink
            '#06B6D4', // Cyan
        ];
        $index = 0;

        foreach ($rawMethods as $item) {
            $name = (string) $item['method_name'];
            $total = (float) $item['total'];
            $count = (int) $item['count'];
            $percentage = $totalRevenue > 0 ? round(($total / $totalRevenue) * 100, 1) : 0;

            $icon = 'bi-credit-card-2-front';
            $lower = strtolower($name);
            if (str_contains($lower, 'qris')) {
                $icon = 'bi-qr-code-scan';
            } elseif (str_contains($lower, 'transfer') || str_contains($lower, 'bank') || str_contains($lower, 'va')) {
                $icon = 'bi-bank';
            } elseif (str_contains($lower, 'cod')) {
                $icon = 'bi-cash-coin';
            } elseif (str_contains($lower, 'midtrans') || str_contains($lower, 'snap')) {
                $icon = 'bi-shield-check';
            }

            $result[] = [
                'name' => $name,
                'count' => $count,
                'total' => $total,
                'percentage' => $percentage,
                'icon' => $icon,
                'color' => $palette[$index % count($palette)],
            ];
            $index++;
        }

        return $result;
    }
}
