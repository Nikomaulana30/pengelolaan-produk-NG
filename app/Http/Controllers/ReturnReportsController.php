<?php

namespace App\Http\Controllers;

use App\Models\CustomerComplaint;
use App\Models\DokumenRetur;
use App\Models\WarehouseVerification;
use App\Models\QualityReinspection;
use App\Models\ProductionRework;
use App\Models\FinalQualityCheck;
use App\Models\ReturnShipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReturnReportsController extends Controller
{
    public function index()
    {
        // Get current date range
        $startDate = request('start_date', now()->startOfMonth()->toDateString());
        $endDate = request('end_date', now()->endOfMonth()->toDateString());
        
        return view('menu-sidebar.reports.return-reports.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dashboardData' => $this->getDashboardData($startDate, $endDate),
            'chartData' => $this->getChartData($startDate, $endDate),
            'tableData' => $this->getTableData($startDate, $endDate)
        ]);
    }

    public function dashboardOverview()
    {
        $today = now()->toDateString();
        $thisMonth = now()->format('Y-m');
        
        $data = [
            // Today's Summary
            'today' => [
                'complaints_new' => CustomerComplaint::whereDate('created_at', $today)->count(),
                'complaints_resolved' => CustomerComplaint::whereDate('updated_at', $today)->where('status', 'completed')->count(),
                'reworks_completed' => ProductionRework::whereDate('tanggal_selesai_rework', $today)->where('status', 'completed')->count(),
                'shipments_delivered' => ReturnShipment::whereDate('delivered_at', $today)->count(),
            ],
            
            // This Month Summary
            'this_month' => [
                'total_complaints' => CustomerComplaint::where('created_at', 'like', $thisMonth.'%')->count(),
                'completed_complaints' => CustomerComplaint::where('created_at', 'like', $thisMonth.'%')->where('status', 'completed')->count(),
                'total_cost' => ProductionRework::where('created_at', 'like', $thisMonth.'%')->sum('actual_biaya'),
                'avg_resolution_time' => $this->calculateAvgResolutionTime($thisMonth),
            ],
            
            // Status Distribution
            'status_distribution' => $this->getStatusDistribution(),
            
            // Performance Metrics
            'performance' => $this->getPerformanceMetrics(),
            
            // Critical Items
            'critical_items' => $this->getCriticalItems()
        ];
        
        return view('menu-sidebar.reports.dashboard-overview', compact('data'));
    }

    public function complaintAnalysis(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period)->toDateString();
        $endDate = now()->toDateString();
        
        $data = [
            'complaint_trends' => $this->getComplaintTrends($startDate, $endDate),
            'priority_distribution' => $this->getPriorityDistribution($startDate, $endDate),
            'resolution_time_analysis' => $this->getResolutionTimeAnalysis($startDate, $endDate),
            'customer_analysis' => $this->getCustomerAnalysis($startDate, $endDate),
            'product_defect_analysis' => $this->getProductDefectAnalysis($startDate, $endDate)
        ];
        
        return view('menu-sidebar.reports.complaint-analysis', compact('data', 'period'));
    }

    public function qualityAnalysis(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period)->toDateString();
        $endDate = now()->toDateString();
        
        $data = [
            'defect_trends' => $this->getDefectTrends($startDate, $endDate),
            'rca_analysis' => $this->getRCAAnalysis($startDate, $endDate),
            'rework_performance' => $this->getReworkPerformance($startDate, $endDate),
            'quality_metrics' => $this->getQualityMetrics($startDate, $endDate),
            'supplier_performance' => $this->getSupplierPerformance($startDate, $endDate)
        ];
        
        return view('menu-sidebar.reports.quality-analysis', compact('data', 'period'));
    }

    public function costAnalysis(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period)->toDateString();
        $endDate = now()->toDateString();
        
        $data = [
            'cost_breakdown' => $this->getCostBreakdown($startDate, $endDate),
            'cost_trends' => $this->getCostTrends($startDate, $endDate),
            'cost_by_product' => $this->getCostByProduct($startDate, $endDate),
            'cost_by_defect_type' => $this->getCostByDefectType($startDate, $endDate),
            'savings_analysis' => $this->getSavingsAnalysis($startDate, $endDate)
        ];
        
        return view('menu-sidebar.reports.cost-analysis', compact('data', 'period'));
    }

    public function exportReport(Request $request)
    {
        $type = $request->get('type'); // excel, pdf
        $report = $request->get('report'); // dashboard, complaint, quality, cost
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Implementation for export functionality
        // This would typically use Laravel Excel or PDF generation packages
        
        return response()->json(['message' => 'Export functionality to be implemented']);
    }

    // Private helper methods
    private function getDashboardData($startDate, $endDate)
    {
        return [
            'total_complaints' => CustomerComplaint::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_reworks' => ProductionRework::whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed_complaints' => CustomerComplaint::whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed')->count(),
            'in_progress_complaints' => CustomerComplaint::whereBetween('created_at', [$startDate, $endDate])->where('status', 'processing')->count(),
            'delivered_shipments' => ReturnShipment::whereIn('status_pengiriman', ['shipped', 'delivered'])->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_shipped_quantity' => ReturnShipment::whereIn('status_pengiriman', ['shipped', 'delivered'])->whereBetween('created_at', [$startDate, $endDate])->sum('quantity_shipped'),
            'total_rework_cost' => ProductionRework::whereBetween('created_at', [$startDate, $endDate])->sum('actual_biaya'),
            'total_shipment_cost' => ReturnShipment::whereIn('status_pengiriman', ['shipped', 'delivered'])->whereBetween('created_at', [$startDate, $endDate])->sum('biaya_pengiriman'),
            'avg_resolution_days' => $this->calculateAvgResolutionTime($startDate, $endDate),
            'customer_satisfaction' => $this->calculateCustomerSatisfaction($startDate, $endDate)
        ];
    }

    private function getChartData($startDate, $endDate)
    {
        // Count complaints by workflow stage within date range
        $complaintsInRange = CustomerComplaint::whereBetween('created_at', [$startDate, $endDate])->pluck('id');
        
        // Get daily complaint data
        $dailyData = CustomerComplaint::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Prepare labels and data for trend chart
        $labels = $dailyData->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        })->toArray();
        
        $returns = $dailyData->pluck('count')->toArray();
        
        // Status distribution counts
        $pendingCount = CustomerComplaint::whereIn('id', $complaintsInRange)
            ->whereIn('status', ['draft', 'submitted'])
            ->count();
        
        $inProgressCount = CustomerComplaint::whereIn('id', $complaintsInRange)
            ->where('status', 'processing')
            ->count();
        
        $completedCount = CustomerComplaint::whereIn('id', $complaintsInRange)
            ->where('status', 'completed')
            ->count();
        
        return [
            'labels' => !empty($labels) ? $labels : ['No Data'],
            'returns' => !empty($returns) ? $returns : [0],
            'status_labels' => ['Completed', 'In Progress', 'Pending'],
            'status_data' => [$completedCount, $inProgressCount, $pendingCount],
            'complaint_daily' => $dailyData->pluck('count', 'date'),
            'status_distribution' => [
                'pending' => $pendingCount,
                'in_progress' => $inProgressCount,
                'completed' => $completedCount,
            ],
            'defect_types' => QualityReinspection::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('jenis_defect, COUNT(*) as count')
                ->groupBy('jenis_defect')
                ->pluck('count', 'jenis_defect')
        ];
    }

    private function getTableData($startDate, $endDate)
    {
        return [
            'recent_complaints' => CustomerComplaint::with('staffExim')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
                
            'delivered_shipments' => ReturnShipment::with([
                'finalQualityCheck.productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint',
                'warehouseStaff'
            ])
                ->whereIn('status_pengiriman', ['shipped', 'delivered'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get(),
                
            'pending_actions' => $this->getPendingActions(),
            
            'top_defects' => QualityReinspection::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('jenis_defect, COUNT(*) as count, AVG(quantity_defect) as avg_qty')
                ->groupBy('jenis_defect')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get()
        ];
    }

    private function calculateAvgResolutionTime($startDate, $endDate = null)
    {
        $query = CustomerComplaint::where('status', 'completed');
        
        if ($endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $query->where('created_at', 'like', $startDate.'%');
        }
        
        $complaints = $query->get();
        
        if ($complaints->isEmpty()) return 0;
        
        $totalDays = $complaints->sum(function($complaint) {
            return $complaint->created_at->diffInDays($complaint->updated_at);
        });
        
        return round($totalDays / $complaints->count(), 1);
    }

    private function calculateCustomerSatisfaction($startDate, $endDate)
    {
        $avgRating = ReturnShipment::whereHas('finalQualityCheck.productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->whereNotNull('rating_customer')->avg('rating_customer');
        
        return round($avgRating ?? 0, 1);
    }

    private function getStatusDistribution()
    {
        return CustomerComplaint::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    private function getPerformanceMetrics()
    {
        return [
            'on_time_completion' => $this->calculateOnTimeCompletion(),
            'first_time_fix_rate' => $this->calculateFirstTimeFixRate(),
            'rework_success_rate' => $this->calculateReworkSuccessRate(),
            'cost_per_complaint' => $this->calculateCostPerComplaint()
        ];
    }

    private function getCriticalItems()
    {
        return [
            'overdue_complaints' => CustomerComplaint::where('status', 'processing')
                ->where('created_at', '<', now()->subDays(7))
                ->count(),
                
            'high_cost_reworks' => ProductionRework::where('actual_biaya', '>', 1000000)
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
                
            'critical_defects' => QualityReinspection::where('severity_level', 'critical')
                ->where('created_at', '>=', now()->startOfWeek())
                ->count()
        ];
    }

    private function getPendingActions()
    {
        return [
            'pending_dokumen_retur' => DokumenRetur::where('status', 'draft')->count(),
            'pending_verification' => WarehouseVerification::where('status', 'draft')->count(),
            'pending_inspection' => QualityReinspection::where('status', 'draft')->count(),
            'pending_rework' => ProductionRework::where('status', 'draft')->count(),
            'pending_quality_check' => FinalQualityCheck::where('status', 'draft')->count(),
            'pending_shipment' => ReturnShipment::where('status', 'draft')->count()
        ];
    }

    // Additional helper methods would be implemented here for detailed analytics
    private function calculateOnTimeCompletion() { return 85.5; } // Placeholder
    private function calculateFirstTimeFixRate() { return 78.2; } // Placeholder
    private function calculateReworkSuccessRate() { return 92.1; } // Placeholder
    private function calculateCostPerComplaint() { return 750000; } // Placeholder
    
    // More detailed analysis methods would be implemented based on specific requirements
    private function getComplaintTrends($startDate, $endDate) { return []; }
    private function getPriorityDistribution($startDate, $endDate) { return []; }
    private function getResolutionTimeAnalysis($startDate, $endDate) { return []; }
    private function getCustomerAnalysis($startDate, $endDate) { return []; }
    private function getProductDefectAnalysis($startDate, $endDate) { return []; }
    private function getDefectTrends($startDate, $endDate) { return []; }
    private function getRCAAnalysis($startDate, $endDate) { return []; }
    private function getReworkPerformance($startDate, $endDate) { return []; }
    private function getQualityMetrics($startDate, $endDate) { return []; }
    private function getSupplierPerformance($startDate, $endDate) { return []; }
    private function getCostBreakdown($startDate, $endDate) { return []; }
    private function getCostTrends($startDate, $endDate) { return []; }
    private function getCostByProduct($startDate, $endDate) { return []; }
    private function getCostByDefectType($startDate, $endDate) { return []; }
    private function getSavingsAnalysis($startDate, $endDate) { return []; }
}