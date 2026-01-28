<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PpicFinanceSheet implements FromCollection, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($finance) {
            return [
                $finance->rcaAnalysis->nomor_rca ?? '-',
                $finance->tanggal_approval ?? '-',
                $finance->rcaAnalysis->masterProduk->nama_produk ?? '-',
                $finance->rcaAnalysis->masterProduk->kode_produk ?? '-',
                ucfirst($finance->status ?? '-'),
                'Rp ' . number_format($finance->biaya_perbaikan ?? 0, 0, ',', '.'),
                $finance->catatan_approval ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Nomor RCA', 'Tanggal Approval', 'Produk', 'Kode Produk', 'Status', 'Biaya Perbaikan', 'Catatan'];
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
