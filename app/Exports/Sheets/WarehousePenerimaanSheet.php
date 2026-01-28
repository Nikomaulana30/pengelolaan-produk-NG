<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehousePenerimaanSheet implements FromCollection, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($penerimaan) {
            return [
                $penerimaan->nomor_dokumen ?? '-',
                $penerimaan->tanggal_input ?? '-',
                $penerimaan->nama_barang ?? '-',
                $penerimaan->sku ?? '-',
                $penerimaan->batch_number ?? '-',
                $penerimaan->qty_baik ?? 0,
                $penerimaan->qty_rusak ?? 0,
                $penerimaan->penginput ?? '-',
                ucfirst($penerimaan->status ?? '-'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Nomor Dokumen', 'Tanggal', 'Nama Barang', 'SKU', 'Batch Number', 'Qty Baik', 'Qty Rusak', 'Penginput', 'Status'];
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
