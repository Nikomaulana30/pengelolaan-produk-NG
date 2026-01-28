<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehouseReturSheet implements FromCollection, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($retur) {
            return [
                $retur->no_retur ?? '-',
                $retur->tanggal_retur ?? '-',
                $retur->produk->nama_produk ?? '-',
                $retur->produk->kode_produk ?? '-',
                $retur->jumlah_retur ?? 0,
                $retur->vendor->nama_vendor ?? '-',
                $retur->alasan_retur ?? '-',
                ucfirst($retur->status_approval ?? '-'),
            ];
        });
    }

    public function headings(): array
    {
        return ['No Retur', 'Tanggal', 'Produk', 'Kode Produk', 'Jumlah', 'Vendor', 'Alasan', 'Status'];
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
