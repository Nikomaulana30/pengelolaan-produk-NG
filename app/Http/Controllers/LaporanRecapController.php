<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RcaAnalysis;
use App\Models\FinanceApproval;
use App\Models\QualityInspection;
use App\Models\MasterDefect;
use App\Models\PenerimaanBarang;
use App\Models\ReturBarang;
use App\Models\PenyimpananNg;
use App\Models\ScrapDisposal;
use Maatwebsite\Excel\Facades\Excel;

class LaporanRecapController extends Controller
{
    /**
     * Display comprehensive recap report
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('start_date')) : now()->subMonths(1);
        $endDate = $request->input('end_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('end_date')) : now();

        // PPIC Data
        $rcaAnalysis = RcaAnalysis::whereBetween('tanggal_analisa', [$startDate, $endDate])->get();
        $financeApprovals = FinanceApproval::with(['rcaAnalysis'])->whereBetween('tanggal_approval', [$startDate, $endDate])->get();
        
        // QA Data
        $qualityInspections = QualityInspection::whereBetween('tanggal_inspeksi', [$startDate, $endDate])->get();
        $defects = MasterDefect::all();
        $defectStats = $this->getDefectStatistics($qualityInspections);

        // Warehouse Data
        $penerimaanBarang = PenerimaanBarang::whereBetween('tanggal_input', [$startDate, $endDate])->get();
        $returBarang = ReturBarang::whereBetween('tanggal_retur', [$startDate, $endDate])->get();
        $penyimpanan = PenyimpananNg::whereBetween('tanggal_penyimpanan', [$startDate, $endDate])->get();
        $scrapDisposal = ScrapDisposal::whereBetween('tanggal_scrap', [$startDate, $endDate])->get();

        // Summary Statistics
        $summary = [
            'ppic' => [
                'rca_total' => $rcaAnalysis->count(),
                'rca_open' => $rcaAnalysis->where('status_rca', 'open')->count(),
                'rca_in_progress' => $rcaAnalysis->where('status_rca', 'in_progress')->count(),
                'rca_closed' => $rcaAnalysis->where('status_rca', 'closed')->count(),
                'finance_approved' => $financeApprovals->where('status_approval', 'approved')->count(),
                'finance_pending' => $financeApprovals->where('status_approval', 'pending')->count(),
            ],
            'qa' => [
                'inspection_total' => $qualityInspections->count(),
                'inspection_passed' => $qualityInspections->where('hasil', 'OK')->count(),
                'inspection_rejected' => $qualityInspections->where('hasil', 'NG')->count(),
                'inspection_rework' => $qualityInspections->where('hasil', 'REWORK')->count(),
                'defect_total' => $qualityInspections->where('hasil', 'NG')->count(),
            ],
            'warehouse' => [
                'penerimaan_total' => $penerimaanBarang->count(),
                'retur_total' => $returBarang->count(),
                'penyimpanan_active' => $penyimpanan->where('status_barang', 'disimpan')->count(),
                'scrap_total' => $scrapDisposal->count(),
            ]
        ];

        return view('menu-sidebar.laporan-recap', compact(
            'rcaAnalysis',
            'financeApprovals',
            'qualityInspections',
            'defects',
            'defectStats',
            'penerimaanBarang',
            'returBarang',
            'penyimpanan',
            'scrapDisposal',
            'summary',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export PPIC data
     */
    public function exportPpic(Request $request)
    {
        $startDate = $request->input('start_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('start_date')) : now()->subMonths(1);
        $endDate = $request->input('end_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('end_date')) : now();
        $format = $request->input('format', 'excel');

        $rcaAnalysis = RcaAnalysis::whereBetween('tanggal_analisa', [$startDate, $endDate])->get();
        $financeApprovals = FinanceApproval::with(['rcaAnalysis'])->whereBetween('tanggal_approval', [$startDate, $endDate])->get();

        if ($format === 'pdf') {
            return $this->generatePdfPpic($rcaAnalysis, $financeApprovals, $startDate, $endDate);
        }

        return Excel::download(new \App\Exports\PpicRecapExport($rcaAnalysis, $financeApprovals), 'PPIC_Recap_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Export QA data
     */
    public function exportQa(Request $request)
    {
        $startDate = $request->input('start_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('start_date')) : now()->subMonths(1);
        $endDate = $request->input('end_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('end_date')) : now();
        $format = $request->input('format', 'excel');

        $qualityInspections = QualityInspection::with(['produk'])->whereBetween('tanggal_inspeksi', [$startDate, $endDate])->get();
        $qualityApprovals = QualityApproval::whereBetween('tanggal_approval', [$startDate, $endDate])->get();

        if ($format === 'pdf') {
            return $this->generatePdfQa($qualityInspections, $qualityApprovals, $startDate, $endDate);
        }

        return Excel::download(new \App\Exports\QaRecapExport($qualityInspections), 'QA_Recap_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Export Warehouse data
     */
    public function exportWarehouse(Request $request)
    {
        $startDate = $request->input('start_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('start_date')) : now()->subMonths(1);
        $endDate = $request->input('end_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('end_date')) : now();
        $format = $request->input('format', 'excel');

        $penerimaanBarang = PenerimaanBarang::whereBetween('tanggal_input', [$startDate, $endDate])->get();
        $returBarang = ReturBarang::with(['produk', 'vendor'])->whereBetween('tanggal_retur', [$startDate, $endDate])->get();
        $penyimpanan = PenyimpananNg::whereBetween('tanggal_penyimpanan', [$startDate, $endDate])->get();
        $scrapDisposal = ScrapDisposal::whereBetween('tanggal_scrap', [$startDate, $endDate])->get();

        if ($format === 'pdf') {
            return $this->generatePdfWarehouse($penerimaanBarang, $returBarang, $penyimpanan, $scrapDisposal, $startDate, $endDate);
        }

        return Excel::download(
            new \App\Exports\WarehouseRecapExport($penerimaanBarang, $returBarang, $penyimpanan, $scrapDisposal),
            'Warehouse_Recap_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export comprehensive recap (all modules)
     */
    public function exportComprehensive(Request $request)
    {
        $startDate = $request->input('start_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('start_date')) : now()->subMonths(1);
        $endDate = $request->input('end_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('end_date')) : now();
        $format = $request->input('format', 'excel');

        $data = [
            'ppic' => [
                'rca' => RcaAnalysis::whereBetween('tanggal_analisa', [$startDate, $endDate])->get(),
                'finance' => FinanceApproval::with(['rcaAnalysis'])->whereBetween('tanggal_approval', [$startDate, $endDate])->get(),
            ],
            'qa' => [
                'inspections' => QualityInspection::whereBetween('tanggal_inspeksi', [$startDate, $endDate])->get(),
            ],
            'warehouse' => [
                'penerimaan' => PenerimaanBarang::whereBetween('tanggal_input', [$startDate, $endDate])->get(),
                'retur' => ReturBarang::with(['produk', 'vendor'])->whereBetween('tanggal_retur', [$startDate, $endDate])->get(),
                'penyimpanan' => PenyimpananNg::whereBetween('tanggal_penyimpanan', [$startDate, $endDate])->get(),
                'scrap' => ScrapDisposal::whereBetween('tanggal_scrap', [$startDate, $endDate])->get(),
            ]
        ];

        if ($format === 'pdf') {
            return $this->generatePdfComprehensive($data, $startDate, $endDate);
        }

        return Excel::download(
            new \App\Exports\ComprehensiveRecapExport($data),
            'Comprehensive_Recap_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Get defect statistics
     */
    private function getDefectStatistics($qualityInspections)
    {
        $defectStats = [];
        
        foreach ($qualityInspections as $inspection) {
            $defects = json_decode($inspection->defects_found, true) ?? [];
            
            foreach ($defects as $defect) {
                $defectId = $defect['defect_id'] ?? null;
                if ($defectId) {
                    if (!isset($defectStats[$defectId])) {
                        $defectStats[$defectId] = ['count' => 0, 'name' => $defect['defect_name'] ?? 'Unknown'];
                    }
                    $defectStats[$defectId]['count']++;
                }
            }
        }

        // Sort by count descending
        uasort($defectStats, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        return $defectStats;
    }

    /**
     * Generate PDF for PPIC Report
     */
    private function generatePdfPpic($rcaAnalysis, $financeApprovals, $startDate, $endDate)
    {
        $html = view('exports.ppic-recap-pdf', compact('rcaAnalysis', 'financeApprovals', 'startDate', 'endDate'))->render();
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('PPIC_Recap_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for QA Report
     */
    private function generatePdfQa($qualityInspections, $qualityApprovals, $startDate, $endDate)
    {
        $html = view('exports.qa-recap-pdf', compact('qualityInspections', 'qualityApprovals', 'startDate', 'endDate'))->render();
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('QA_Recap_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for Warehouse Report
     */
    private function generatePdfWarehouse($penerimaanBarang, $returBarang, $penyimpanan, $scrapDisposal, $startDate, $endDate)
    {
        $html = view('exports.warehouse-recap-pdf', compact('penerimaanBarang', 'returBarang', 'penyimpanan', 'scrapDisposal', 'startDate', 'endDate'))->render();
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Warehouse_Recap_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for Comprehensive Report
     */
    private function generatePdfComprehensive($data, $startDate, $endDate)
    {
        $rcaAnalysis = $data['ppic']['rca'];
        $qualityInspections = $data['qa']['inspections'];
        $penerimaanBarang = $data['warehouse']['penerimaan'];
        $returBarang = $data['warehouse']['retur'];
        $penyimpanan = $data['warehouse']['penyimpanan'];
        $scrapDisposal = $data['warehouse']['scrap'];
        
        $html = view('exports.comprehensive-recap-pdf', compact(
            'rcaAnalysis', 
            'qualityInspections', 
            'penerimaanBarang', 
            'returBarang', 
            'penyimpanan', 
            'scrapDisposal',
            'startDate', 
            'endDate'
        ))->render();
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Laporan_Recap_Comprehensive_' . now()->format('Y-m-d') . '.pdf');
    }
}
