<?php

namespace App\Http\Controllers;

use App\Models\MasterVendor;
use App\Models\ReturBarang;
use App\Models\RcaAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorScorecardController extends Controller
{
    /**
     * Display a listing of vendor scorecards
     */
    public function index(Request $request)
    {
        // Get all vendors with return statistics
        $allVendors = MasterVendor::with(['returBarangs'])
            ->where('is_active', true)
            ->get()
            ->map(function ($vendor) {
                return $this->calculateVendorMetrics($vendor);
            })
            ->sortByDesc('return_rate')
            ->values();

        // Paginate the collection manually
        $perPage = 10;
        $page = request()->get('page', 1);
        $vendors = collect($allVendors)
            ->forPage($page, $perPage)
            ->values();

        // Create manual pagination object
        $vendors = new \Illuminate\Pagination\Paginator(
            $vendors,
            $perPage,
            $page,
            [
                'path' => route('vendor-scorecard.index'),
                'query' => request()->query(),
            ]
        );

        // Summary statistics
        $totalVendors = MasterVendor::where('is_active', true)->count();
        $avgReturnRate = $this->calculateAverageReturnRate();
        $topDefects = $this->getTopDefectsAcrossVendors();
        $performanceDistribution = $this->getPerformanceDistribution();

        return view('menu-sidebar.vendor-scorecard.index', compact(
            'vendors',
            'totalVendors',
            'avgReturnRate',
            'topDefects',
            'performanceDistribution'
        ));
    }

    /**
     * Display the specified vendor scorecard
     */
    public function show(MasterVendor $masterVendor)
    {
        $vendor = $masterVendor->load(['returBarangs', 'returBarangs.produk']);
        
        // Calculate detailed metrics
        $metrics = $this->calculateVendorMetrics($vendor);
        
        // Get return history with RCA analysis
        $returHistory = ReturBarang::where('vendor_id', $vendor->id)
            ->with(['produk', 'rcaAnalyses'])
            ->orderBy('tanggal_retur', 'desc')
            ->paginate(10);
        
        // Get RCA analysis linked to this vendor's returns
        $rcaAnalyses = RcaAnalysis::whereIn('retur_barang_id', 
            ReturBarang::where('vendor_id', $vendor->id)->pluck('id')
        )
        ->with(['masterDefect', 'returBarang.produk'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        // Calculate defect distribution
        $defectDistribution = $this->getVendorDefectDistribution($vendor->id);
        
        // Calculate monthly trend
        $monthlyTrend = $this->getVendorMonthlyTrend($vendor->id);
        
        // Get similar vendors for comparison
        $similarVendors = $this->getSimilarVendorsForComparison($vendor->id);

        return view('menu-sidebar.vendor-scorecard.show', compact(
            'vendor',
            'metrics',
            'returHistory',
            'rcaAnalyses',
            'defectDistribution',
            'monthlyTrend',
            'similarVendors'
        ));
    }

    /**
     * Calculate comprehensive metrics for a vendor
     */
    private function calculateVendorMetrics($vendor)
    {
        // Total returns
        $totalReturns = $vendor->returBarangs->count();
        
        // Returns by status
        $approvedReturns = $vendor->returBarangs->where('status_approval', 'approved')->count();
        $pendingReturns = $vendor->returBarangs->where('status_approval', 'pending')->count();
        $rejectedReturns = $vendor->returBarangs->where('status_approval', 'rejected')->count();
        
        // Return rate calculation
        $approval_rate = $totalReturns > 0 ? ($approvedReturns / $totalReturns * 100) : 0;
        
        // Average return qty
        $totalQty = $vendor->returBarangs->sum('jumlah_retur');
        $avgQtyPerReturn = $totalReturns > 0 ? ($totalQty / $totalReturns) : 0;
        
        // RCA linked to this vendor
        $rcaCount = RcaAnalysis::whereIn('retur_barang_id',
            ReturBarang::where('vendor_id', $vendor->id)->pluck('id')
        )->count();
        
        // Performance score (0-100)
        $performanceScore = $this->calculatePerformanceScore(
            $approval_rate,
            $totalReturns,
            $rcaCount
        );
        
        // Performance rating
        $performanceRating = $this->getPerformanceRating($performanceScore);
        
        // Return rate percentage
        $return_rate = $totalReturns > 0 ? ($totalReturns / max($totalReturns, 1) * 10) : 0;

        return (object)[
            'id' => $vendor->id,
            'nama_vendor' => $vendor->nama_vendor,
            'kebijakan_retur' => $vendor->kebijakan_retur,
            'total_returns' => $totalReturns,
            'approved_returns' => $approvedReturns,
            'pending_returns' => $pendingReturns,
            'rejected_returns' => $rejectedReturns,
            'approval_rate' => round($approval_rate, 2),
            'total_qty_returned' => $totalQty,
            'avg_qty_per_return' => round($avgQtyPerReturn, 2),
            'rca_count' => $rcaCount,
            'performance_score' => round($performanceScore, 2),
            'performance_rating' => $performanceRating,
            'return_rate' => $return_rate,
            'phone' => $vendor->telepon,
            'email' => $vendor->email,
        ];
    }

    /**
     * Calculate performance score (0-100)
     */
    private function calculatePerformanceScore($approvalRate, $totalReturns, $rcaCount)
    {
        $score = 100;
        
        // Deduct for low approval rate
        $score -= (100 - $approvalRate) * 0.3;
        
        // Deduct for many returns (normalized)
        $returnPenalty = min($totalReturns / 50 * 100, 30);
        $score -= $returnPenalty * 0.3;
        
        // Deduct for many RCA analyses (indicates issues)
        $rcaPenalty = min($rcaCount / 10 * 100, 20);
        $score -= $rcaPenalty * 0.2;
        
        return max($score, 0);
    }

    /**
     * Get performance rating based on score
     */
    private function getPerformanceRating($score)
    {
        if ($score >= 80) return 'Excellent';
        if ($score >= 60) return 'Good';
        if ($score >= 40) return 'Fair';
        return 'Poor';
    }

    /**
     * Calculate average return rate across all vendors
     */
    private function calculateAverageReturnRate()
    {
        $vendors = MasterVendor::where('is_active', true)->with('returBarangs')->get();
        
        $totalReturns = 0;
        $totalVendors = 0;
        
        foreach ($vendors as $vendor) {
            if ($vendor->returBarangs->count() > 0) {
                $totalReturns += $vendor->returBarangs->count();
                $totalVendors++;
            }
        }
        
        return $totalVendors > 0 ? round($totalReturns / $totalVendors, 2) : 0;
    }

    /**
     * Get top defects across all vendors
     */
    private function getTopDefectsAcrossVendors()
    {
        return RcaAnalysis::with('masterDefect')
            ->groupBy('kode_defect')
            ->selectRaw('kode_defect, count(*) as count')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($rca) {
                return [
                    'kode_defect' => $rca->kode_defect,
                    'nama_defect' => $rca->masterDefect?->nama_defect ?? 'N/A',
                    'count' => $rca->count
                ];
            });
    }

    /**
     * Get performance distribution
     */
    private function getPerformanceDistribution()
    {
        $vendors = MasterVendor::where('is_active', true)->with('returBarangs')->get();
        
        $excellent = $good = $fair = $poor = 0;
        
        foreach ($vendors as $vendor) {
            $metrics = $this->calculateVendorMetrics($vendor);
            
            if ($metrics->performance_score >= 80) $excellent++;
            elseif ($metrics->performance_score >= 60) $good++;
            elseif ($metrics->performance_score >= 40) $fair++;
            else $poor++;
        }
        
        return [
            'excellent' => $excellent,
            'good' => $good,
            'fair' => $fair,
            'poor' => $poor,
        ];
    }

    /**
     * Get defect distribution for a specific vendor
     */
    private function getVendorDefectDistribution($vendorId)
    {
        return RcaAnalysis::whereIn('retur_barang_id',
            ReturBarang::where('vendor_id', $vendorId)->pluck('id')
        )
        ->with('masterDefect')
        ->groupBy('kode_defect')
        ->selectRaw('kode_defect, count(*) as count')
        ->orderByDesc('count')
        ->get()
        ->map(function ($rca) {
            return [
                'kode_defect' => $rca->kode_defect,
                'nama_defect' => $rca->masterDefect?->nama_defect ?? 'N/A',
                'count' => $rca->count
            ];
        });
    }

    /**
     * Get monthly trend for a specific vendor
     */
    private function getVendorMonthlyTrend($vendorId)
    {
        return ReturBarang::where('vendor_id', $vendorId)
            ->selectRaw('YEAR(tanggal_retur) as year, MONTH(tanggal_retur) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month . '/' . $item->year,
                    'count' => $item->count
                ];
            });
    }

    /**
     * Get similar vendors for comparison
     */
    private function getSimilarVendorsForComparison($vendorId)
    {
        $mainVendor = MasterVendor::find($vendorId)->load('returBarangs');
        $mainMetrics = $this->calculateVendorMetrics($mainVendor);
        
        return MasterVendor::where('is_active', true)
            ->where('id', '!=', $vendorId)
            ->with('returBarangs')
            ->get()
            ->map(function ($vendor) {
                return $this->calculateVendorMetrics($vendor);
            })
            ->sortBy(function ($metrics) use ($mainMetrics) {
                // Sort by closest performance score
                return abs($metrics->performance_score - $mainMetrics->performance_score);
            })
            ->take(5)
            ->values();
    }
}
