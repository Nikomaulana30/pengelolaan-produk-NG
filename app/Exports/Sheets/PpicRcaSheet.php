<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PpicRcaSheet implements FromCollection, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($rca) {
            return [
                $rca->nomor_rca ?? '-',
                $rca->tanggal_analisa ?? '-',
                $rca->masterProduk->nama_produk ?? '-',
                $rca->masterProduk->kode_produk ?? '-',
                ucfirst($rca->status_rca ?? '-'),
                $rca->root_cause ?? '-',
                $rca->tindakan_perbaikan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Nomor RCA', 'Tanggal', 'Produk', 'Kode Produk', 'Status', 'Root Cause', 'Tindakan Perbaikan'];
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
