<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehousePenyimpananSheet implements FromCollection, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($simpan) {
            return [
                $simpan->nomor_storage ?? '-',
                $simpan->tanggal_penyimpanan ?? '-',
                $simpan->nama_barang ?? '-',
                $simpan->nomor_referensi ?? '-',
                $simpan->lokasi_lengkap ?? '-',
                $simpan->qty_awal ?? 0,
                $simpan->qty_setelah_perbaikan ?? 0,
                ucfirst(str_replace('_', ' ', $simpan->status_barang ?? '-')),
                ucfirst($simpan->status ?? '-'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Nomor Storage', 'Tanggal', 'Nama Barang', 'No Referensi', 'Lokasi', 'Qty Awal', 'Qty Setelah Perbaikan', 'Status Barang', 'Status'];
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
