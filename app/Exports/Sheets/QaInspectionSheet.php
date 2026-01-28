<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QaInspectionSheet implements FromCollection, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($inspection) {
            return [
                $inspection->nomor_laporan ?? '-',
                $inspection->tanggal_inspeksi ?? '-',
                $inspection->product ?? '-',
                $inspection->part_no ?? '-',
                ucfirst($inspection->hasil ?? '-'),
                ucfirst($inspection->status_approval ?? '-'),
                $inspection->made_by ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Nomor Laporan', 'Tanggal', 'Produk', 'Part No', 'Hasil', 'Status Approval', 'Inspector'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'f093fb']],
            ],
        ];
    }
}
