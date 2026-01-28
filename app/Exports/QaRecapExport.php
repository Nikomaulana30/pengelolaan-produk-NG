<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QaRecapExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $qualityInspections;

    public function __construct($qualityInspections)
    {
        $this->qualityInspections = $qualityInspections;
    }

    public function collection()
    {
        $data = collect();

        foreach ($this->qualityInspections as $inspection) {
            $data->push([
                'nomor_inspeksi' => $inspection->nomor_laporan ?? '-',
                'tanggal_inspeksi' => $inspection->tanggal_inspeksi ?? '-',
                'produk' => $inspection->product ?? '-',
                'part_no' => $inspection->part_no ?? '-',
                'hasil' => ucfirst($inspection->hasil ?? '-'),
                'status_approval' => ucfirst($inspection->status_approval ?? '-'),
                'inspector' => $inspection->made_by ?? '-',
                'approved_by' => $inspection->approved_by ?? '-',
                'catatan' => $inspection->catatan ?? '-',
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nomor Laporan',
            'Tanggal Inspeksi',
            'Produk',
            'Part No',
            'Hasil',
            'Status Approval',
            'Inspector',
            'Approved By',
            'Catatan'
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
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'f093fb']],
            ],
        ];
    }
}
