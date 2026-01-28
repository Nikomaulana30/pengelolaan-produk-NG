<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PpicRecapExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $rcaAnalysis;
    private $financeApprovals;

    public function __construct($rcaAnalysis, $financeApprovals)
    {
        $this->rcaAnalysis = $rcaAnalysis;
        $this->financeApprovals = $financeApprovals;
    }

    public function collection()
    {
        // Combine both RCA and Finance Approval data
        $data = collect();
        
        foreach ($this->rcaAnalysis as $rca) {
            $data->push([
                'type' => 'RCA Analysis',
                'id' => $rca->id,
                'nomor_rca' => $rca->nomor_rca ?? '-',
                'tanggal' => $rca->tanggal_analisa ?? '-',
                'produk' => $rca->masterProduk->nama_produk ?? '-',
                'kode_produk' => $rca->masterProduk->kode_produk ?? '-',
                'status' => ucfirst($rca->status_rca ?? '-'),
                'root_cause' => $rca->root_cause ?? '-',
                'tindakan_perbaikan' => $rca->tindakan_perbaikan ?? '-',
            ]);
        }

        foreach ($this->financeApprovals as $finance) {
            $data->push([
                'type' => 'Finance Approval',
                'id' => $finance->id,
                'nomor_rca' => $finance->rcaAnalysis->nomor_rca ?? '-',
                'tanggal' => $finance->tanggal_approval ?? '-',
                'produk' => $finance->rcaAnalysis->masterProduk->nama_produk ?? '-',
                'kode_produk' => $finance->rcaAnalysis->masterProduk->kode_produk ?? '-',
                'status' => ucfirst($finance->status ?? '-'),
                'biaya_perbaikan' => 'Rp ' . number_format($finance->biaya_perbaikan ?? 0, 0, ',', '.'),
                'catatan' => $finance->catatan_approval ?? '-',
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Type',
            'ID',
            'Nomor RCA',
            'Tanggal',
            'Produk',
            'Kode Produk',
            'Status',
            'Keterangan',
            'Detail'
        ];
    }

    public function map($row): array
    {
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '667eea']],
            ],
        ];
    }
}
