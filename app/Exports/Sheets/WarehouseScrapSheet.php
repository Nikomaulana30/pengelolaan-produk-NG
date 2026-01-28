<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehouseScrapSheet implements FromCollection, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($scrap) {
            return [
                $scrap->nomor_scrap ?? '-',
                $scrap->tanggal_scrap ?? '-',
                $scrap->nama_barang ?? '-',
                $scrap->nomor_referensi ?? '-',
                $scrap->quantity ?? 0,
                $scrap->alasan_scrap ?? '-',
                $scrap->metode_pembuangan ?? '-',
                ucfirst($scrap->status_approval ?? '-'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Nomor Scrap', 'Tanggal', 'Nama Barang', 'No Referensi', 'Quantity', 'Alasan', 'Metode Pembuangan', 'Status'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '43e97b']],
            ],
        ];
    }
}
