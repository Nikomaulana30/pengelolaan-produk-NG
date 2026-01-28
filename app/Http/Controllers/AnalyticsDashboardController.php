<?php

namespace App\Http\Controllers;

use App\Models\MasterVendor;
use App\Models\ReturBarang;
use App\Models\RcaAnalysis;
use App\Models\MasterDefect;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsDashboardController extends Controller
{
    /**
     * Display the analytics dashboard
     */
    public function index(Request $request)
    {
        // Get quality metrics from service
        $qualityMetrics = AnalyticsService::getDashboardMetrics();
        
        // KPI Summary Cards
        $kpiMetrics = $this->calculateKPIMetrics();
        
        // Charts data
        $vendorPerformanceChart = $this->getVendorPerformanceChartData();
        $returnTrendChart = $this->getReturnTrendChartData();
        $defectDistributionChart = $this->getDefectDistributionChartData();
        $returnStatusChart = $this->getReturnStatusChartData();
        $rcaStatusChart = $this->getRCAStatusChartData();
        
        // Top performers
        $topVendors = $this->getTopVendors(5);
        $bottomVendors = $this->getBottomVendors(5);
        $topDefects = $this->getTopDefects(10);
        
        // Recent activity
        $recentReturns = ReturBarang::with(['vendor', 'produk'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $recentRCAs = RcaAnalysis::with(['masterDefect', 'returBarang.vendor', 'returBarang.produk'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('menu-sidebar.reports.return-analysis', compact(
            'qualityMetrics',
            'kpiMetrics',
            'vendorPerformanceChart',
            'returnTrendChart',
            'defectDistributionChart',
            'returnStatusChart',
            'rcaStatusChart',
            'topVendors',
            'bottomVendors',
            'topDefects',
            'recentReturns',
            'recentRCAs'
        ));
    }

    /**
     * Calculate KPI metrics
     */
    private function calculateKPIMetrics()
    {
        // Total metrics
        $totalReturns = ReturBarang::count();
        $totalRCAs = RcaAnalysis::count();
        $totalVendors = MasterVendor::where('is_active', true)->count();
        
        // Return status breakdown
        $approvedReturns = ReturBarang::where('status_approval', 'approved')->count();
        $pendingReturns = ReturBarang::where('status_approval', 'pending')->count();
        $rejectedReturns = ReturBarang::where('status_approval', 'rejected')->count();
        
        // Rates
        $approvalRate = $totalReturns > 0 ? round(($approvedReturns / $totalReturns) * 100, 1) : 0;
        $rejectionRate = $totalReturns > 0 ? round(($rejectedReturns / $totalReturns) * 100, 1) : 0;
        
        // RCA status
        $openRCAs = RcaAnalysis::where('status_rca', 'open')->count();
        $closedRCAs = RcaAnalysis::where('status_rca', 'closed')->count();
        $rcaCompletionRate = $totalRCAs > 0 ? round(($closedRCAs / $totalRCAs) * 100, 1) : 0;
        
        // Quantities
        $totalQtyReturned = ReturBarang::sum('jumlah_retur') ?? 0;
        $avgQtyPerReturn = $totalReturns > 0 ? round($totalQtyReturned / $totalReturns, 1) : 0;
        
        // Trends (month over month)
        $thisMonthReturns = ReturBarang::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $lastMonthReturns = ReturBarang::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        $returnTrend = $lastMonthReturns > 0 
            ? round((($thisMonthReturns - $lastMonthReturns) / $lastMonthReturns) * 100, 1) 
            : 0;

        return (object)[
            'total_returns' => $totalReturns,
            'total_rcas' => $totalRCAs,
            'total_vendors' => $totalVendors,
            'approved_returns' => $approvedReturns,
            'pending_returns' => $pendingReturns,
            'rejected_returns' => $rejectedReturns,
            'approval_rate' => $approvalRate,
            'rejection_rate' => $rejectionRate,
            'open_rcas' => $openRCAs,
            'closed_rcas' => $closedRCAs,
            'rca_completion_rate' => $rcaCompletionRate,
            'total_qty_returned' => $totalQtyReturned,
            'avg_qty_per_return' => $avgQtyPerReturn,
            'return_trend' => $returnTrend,
            'this_month_returns' => $thisMonthReturns,
            'last_month_returns' => $lastMonthReturns,
        ];
    }

    /**
     * Get vendor performance chart data
     */
    private function getVendorPerformanceChartData()
    {
        $vendors = MasterVendor::where('is_active', true)
            ->with('returBarangs')
            ->get()
            ->map(function ($vendor) {
                $totalReturns = $vendor->returBarangs->count();
                $approvedReturns = $vendor->returBarangs->where('status_approval', 'approved')->count();
                $approvalRate = $totalReturns > 0 ? round(($approvedReturns / $totalReturns) * 100, 1) : 0;
                
                return [
                    'name' => substr($vendor->nama_vendor, 0, 15),
                    'approval_rate' => $approvalRate,
                    'returns' => $totalReturns,
                ];
            })
            ->sortByDesc('approval_rate')
            ->take(8)
            ->values();

        return [
            'labels' => $vendors->pluck('name')->toArray(),
            'data' => $vendors->pluck('approval_rate')->toArray(),
            'returns' => $vendors->pluck('returns')->toArray(),
        ];
    }

    /**
     * Get return trend chart data (last 12 months)
     */
    private function getReturnTrendChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = ReturBarang::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            
            $data[] = $count;
            $labels[] = $month->format('M y');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get defect distribution chart data
     */
    private function getDefectDistributionChartData()
    {
        $defects = RcaAnalysis::with('masterDefect')
            ->groupBy('kode_defect')
            ->selectRaw('kode_defect, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(8)
            ->get()
            ->map(function ($rca) {
                return [
                    'name' => $rca->masterDefect ? $rca->masterDefect->nama_defect : 'Unknown',
                    'count' => $rca->count,
                ];
            });

        return [
            'labels' => $defects->pluck('name')->toArray(),
            'data' => $defects->pluck('count')->toArray(),
        ];
    }

    /**
     * Get return status breakdown chart
     */
    private function getReturnStatusChartData()
    {
        $approved = ReturBarang::where('status_approval', 'approved')->count();
        $pending = ReturBarang::where('status_approval', 'pending')->count();
        $rejected = ReturBarang::where('status_approval', 'rejected')->count();

        return [
            'labels' => ['Approved', 'Pending', 'Rejected'],
            'data' => [$approved, $pending, $rejected],
            'colors' => ['#28a745', '#ffc107', '#dc3545'],
        ];
    }

    /**
     * Get RCA status breakdown chart
     */
    private function getRCAStatusChartData()
    {
        $open = RcaAnalysis::where('status_rca', 'open')->count();
        $inProgress = RcaAnalysis::where('status_rca', 'in_progress')->count();
        $closed = RcaAnalysis::where('status_rca', 'closed')->count();

        return [
            'labels' => ['Open', 'In Progress', 'Closed'],
            'data' => [$open, $inProgress, $closed],
            'colors' => ['#dc3545', '#ffc107', '#28a745'],
        ];
    }

    /**
     * Get top performing vendors
     */
    private function getTopVendors($limit = 5)
    {
        return MasterVendor::where('is_active', true)
            ->with('returBarangs')
            ->get()
            ->map(function ($vendor) {
                $totalReturns = $vendor->returBarangs->count();
                $approvedReturns = $vendor->returBarangs->where('status_approval', 'approved')->count();
                $approvalRate = $totalReturns > 0 ? round(($approvedReturns / $totalReturns) * 100, 1) : 100;
                
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->nama_vendor,
                    'approval_rate' => $approvalRate,
                    'total_returns' => $totalReturns,
                ];
            })
            ->sortByDesc('approval_rate')
            ->take($limit)
            ->values();
    }

    /**
     * Get bottom performing vendors
     */
    private function getBottomVendors($limit = 5)
    {
        return MasterVendor::where('is_active', true)
            ->with('returBarangs')
            ->get()
            ->filter(function ($vendor) {
                return $vendor->returBarangs->count() > 0;
            })
            ->map(function ($vendor) {
                $totalReturns = $vendor->returBarangs->count();
                $approvedReturns = $vendor->returBarangs->where('status_approval', 'approved')->count();
                $approvalRate = $totalReturns > 0 ? round(($approvedReturns / $totalReturns) * 100, 1) : 100;
                
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->nama_vendor,
                    'approval_rate' => $approvalRate,
                    'total_returns' => $totalReturns,
                ];
            })
            ->sortBy('approval_rate')
            ->take($limit)
            ->values();
    }

    /**
     * Get top defects
     */
    private function getTopDefects($limit = 10)
    {
        return RcaAnalysis::with('masterDefect')
            ->groupBy('kode_defect')
            ->selectRaw('kode_defect, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->map(function ($rca) {
                return [
                    'code' => $rca->kode_defect,
                    'name' => $rca->masterDefect ? $rca->masterDefect->nama_defect : 'Unknown',
                    'count' => $rca->count,
                ];
            });
    }

    /**
     * Export dashboard data as CSV
     */
    public function export()
    {
        $metrics = $this->calculateKPIMetrics();
        $topVendors = $this->getTopVendors(10);
        $topDefects = $this->getTopDefects(20);

        $fileName = 'analytics-dashboard-' . now()->format('Ymd-His') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$fileName"];

        $callback = function () use ($metrics, $topVendors, $topDefects) {
            $file = fopen('php://output', 'w');

            // KPI Metrics Section
            fputcsv($file, ['KPI METRICS']);
            fputcsv($file, ['Total Returns', $metrics->total_returns]);
            fputcsv($file, ['Total RCAs', $metrics->total_rcas]);
            fputcsv($file, ['Total Vendors', $metrics->total_vendors]);
            fputcsv($file, ['Approval Rate (%)', $metrics->approval_rate]);
            fputcsv($file, ['RCA Completion Rate (%)', $metrics->rca_completion_rate]);
            fputcsv($file, []);

            // Top Vendors Section
            fputcsv($file, ['TOP PERFORMING VENDORS']);
            fputcsv($file, ['Vendor Name', 'Approval Rate (%)', 'Total Returns']);
            foreach ($topVendors as $vendor) {
                fputcsv($file, [$vendor['name'], $vendor['approval_rate'], $vendor['total_returns']]);
            }
            fputcsv($file, []);

            // Top Defects Section
            fputcsv($file, ['TOP DEFECTS']);
            fputcsv($file, ['Defect Code', 'Defect Name', 'Count']);
            foreach ($topDefects as $defect) {
                fputcsv($file, [$defect['code'], $defect['name'], $defect['count']]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
