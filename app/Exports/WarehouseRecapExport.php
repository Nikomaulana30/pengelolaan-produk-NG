<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehouseRecapExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $penerimaanBarang;
    private $returBarang;
    private $penyimpanan;
    private $scrapDisposal;

    public function __construct($penerimaanBarang, $returBarang, $penyimpanan, $scrapDisposal)
    {
        $this->penerimaanBarang = $penerimaanBarang;
        $this->returBarang = $returBarang;
        $this->penyimpanan = $penyimpanan;
        $this->scrapDisposal = $scrapDisposal;
    }

    public function collection()
    {
        $data = collect();

        // Penerimaan Barang
        foreach ($this->penerimaanBarang as $penerimaan) {
            $data->push([
                'type' => 'Penerimaan',
                'nomor' => $penerimaan->nomor_dokumen ?? '-',
                'tanggal' => $penerimaan->tanggal_input ?? '-',
                'produk' => $penerimaan->nama_barang ?? '-',
                'kode_produk' => $penerimaan->sku ?? '-',
                'jumlah' => $penerimaan->qty_baik ?? 0,
                'vendor' => $penerimaan->penginput ?? '-',
                'status' => ucfirst($penerimaan->status ?? '-'),
            ]);
        }

        // Retur Barang
        foreach ($this->returBarang as $retur) {
            $data->push([
                'type' => 'Retur',
                'nomor' => $retur->no_retur ?? '-',
                'tanggal' => $retur->tanggal_retur ?? '-',
                'produk' => $retur->produk->nama_produk ?? '-',
                'kode_produk' => $retur->produk->kode_produk ?? '-',
                'jumlah' => $retur->jumlah_retur ?? 0,
                'vendor' => $retur->vendor->nama_vendor ?? '-',
                'alasan' => $retur->alasan_retur ?? '-',
            ]);
        }

        // Penyimpanan
        foreach ($this->penyimpanan as $simpan) {
            $data->push([
                'type' => 'Penyimpanan',
                'nomor' => $simpan->nomor_storage ?? '-',
                'tanggal' => $simpan->tanggal_penyimpanan ?? '-',
                'produk' => $simpan->nama_barang ?? '-',
                'kode_produk' => $simpan->nomor_referensi ?? '-',
                'jumlah' => $simpan->qty_awal ?? 0,
                'lokasi' => $simpan->lokasi_lengkap ?? '-',
                'status' => ucfirst(str_replace('_', ' ', $simpan->status_barang ?? '-')),
            ]);
        }

        // Scrap Disposal
        foreach ($this->scrapDisposal as $scrap) {
            $data->push([
                'type' => 'Scrap/Disposal',
                'nomor' => $scrap->nomor_scrap ?? '-',
                'tanggal' => $scrap->tanggal_scrap ?? '-',
                'produk' => $scrap->nama_barang ?? '-',
                'kode_produk' => $scrap->nomor_referensi ?? '-',
                'jumlah' => $scrap->quantity ?? 0,
                'jenis' => $scrap->metode_pembuangan ?? '-',
                'status' => ucfirst($scrap->status_approval ?? '-'),
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Type',
            'Nomor',
            'Tanggal',
            'Produk',
            'Kode Produk',
            'Jumlah',
            'Vendor/Lokasi/Jenis',
            'Status/Alasan'
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
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '43e97b']],
            ],
        ];
    }
}
