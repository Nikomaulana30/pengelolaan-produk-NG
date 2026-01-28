<?php

namespace App\Services;

use App\Models\PenyimpananNg;
use App\Models\ReturBarang;
use App\Models\ScrapDisposal;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Get summary NG data untuk periode tertentu
     */
    public static function getNgSummary($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        // Total NG items (from penyimpanan_ngs)
        $totalNg = PenyimpananNg::whereBetween('tanggal_penyimpanan', [$startDate, $endDate])
            ->sum('qty_awal');

        // Total Retur
        $totalRetur = ReturBarang::whereBetween('tanggal_retur', [$startDate, $endDate])
            ->sum('jumlah_retur');

        // Total Scrap
        $totalScrap = ScrapDisposal::whereBetween('tanggal_scrap', [$startDate, $endDate])
            ->sum('quantity');

        // Total Rework (qty_setelah_perbaikan)
        $totalRework = PenyimpananNg::whereBetween('tanggal_penyimpanan', [$startDate, $endDate])
            ->sum('qty_setelah_perbaikan');

        return [
            'total_ng' => $totalNg,
            'total_retur' => $totalRetur,
            'total_scrap' => $totalScrap,
            'total_rework' => $totalRework ?? 0,
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
        ];
    }

    /**
     * Get disposition breakdown (%)
     */
    public static function getDispositionBreakdown($startDate = null, $endDate = null)
    {
        $summary = self::getNgSummary($startDate, $endDate);
        $totalNg = $summary['total_ng'];

        if ($totalNg == 0) {
            return [
                'retur_pct' => 0,
                'scrap_pct' => 0,
                'rework_pct' => 0,
                'retur_qty' => 0,
                'scrap_qty' => 0,
                'rework_qty' => 0,
            ];
        }

        return [
            'retur_pct' => round(($summary['total_retur'] / $totalNg) * 100, 2),
            'scrap_pct' => round(($summary['total_scrap'] / $totalNg) * 100, 2),
            'rework_pct' => round(($summary['total_rework'] / $totalNg) * 100, 2),
            'retur_qty' => $summary['total_retur'],
            'scrap_qty' => $summary['total_scrap'],
            'rework_qty' => $summary['total_rework'],
        ];
    }

    /**
     * Get top defect types berdasarkan retur barang
     */
    public static function getTopDefectTypes($limit = 5, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        return ReturBarang::whereBetween('tanggal_retur', [$startDate, $endDate])
            ->selectRaw('alasan_retur, count(*) as count, sum(jumlah_retur) as total_qty')
            ->groupBy('alasan_retur')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'defect_type' => $item->alasan_retur,
                    'frequency' => $item->count,
                    'total_qty' => $item->total_qty,
                ];
            });
    }

    /**
     * Get top vendors by retur
     */
    public static function getTopReturVendors($limit = 5, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        return ReturBarang::whereBetween('tanggal_retur', [$startDate, $endDate])
            ->with('vendor')
            ->selectRaw('vendor_id, count(*) as retur_count, sum(jumlah_retur) as total_retur_qty')
            ->groupBy('vendor_id')
            ->orderByDesc('total_retur_qty')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'vendor_name' => $item->vendor?->nama_vendor ?? 'Unknown',
                    'retur_count' => $item->retur_count,
                    'total_qty' => $item->total_retur_qty,
                ];
            });
    }

    /**
     * Get trending data (comparing this month vs last month)
     */
    public static function getTrending()
    {
        $thisMonth = self::getNgSummary(now()->startOfMonth(), now()->endOfMonth());
        $lastMonth = self::getNgSummary(
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        );

        $calculateTrend = function ($current, $previous) {
            if ($previous == 0) {
                return $current > 0 ? 100 : 0;
            }
            return round((($current - $previous) / $previous) * 100, 2);
        };

        return [
            'ng_trend' => $calculateTrend($thisMonth['total_ng'], $lastMonth['total_ng']),
            'retur_trend' => $calculateTrend($thisMonth['total_retur'], $lastMonth['total_retur']),
            'scrap_trend' => $calculateTrend($thisMonth['total_scrap'], $lastMonth['total_scrap']),
            'rework_trend' => $calculateTrend($thisMonth['total_rework'], $lastMonth['total_rework']),
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
        ];
    }

    /**
     * Get monthly trend (last 6 months)
     */
    public static function getMonthlyTrend()
    {
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $summary = self::getNgSummary(
                $date->startOfMonth(),
                $date->endOfMonth()
            );
            
            $data[] = [
                'month' => $date->format('M Y'),
                'month_num' => $date->month,
                'total_ng' => $summary['total_ng'],
                'retur' => $summary['total_retur'],
                'scrap' => $summary['total_scrap'],
                'rework' => $summary['total_rework'],
            ];
        }

        return $data;
    }

    /**
     * Get quality metrics dashboard
     */
    public static function getDashboardMetrics()
    {
        return [
            'summary' => self::getNgSummary(),
            'disposition' => self::getDispositionBreakdown(),
            'top_defects' => self::getTopDefectTypes(),
            'top_vendors' => self::getTopReturVendors(),
            'trending' => self::getTrending(),
            'monthly_trend' => self::getMonthlyTrend(),
        ];
    }
}
