<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ComprehensiveRecapExport implements WithMultipleSheets
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            'PPIC RCA' => new \App\Exports\Sheets\PpicRcaSheet($this->data['ppic']['rca']),
            'PPIC Finance' => new \App\Exports\Sheets\PpicFinanceSheet($this->data['ppic']['finance']),
            'QA Inspections' => new \App\Exports\Sheets\QaInspectionSheet($this->data['qa']['inspections']),
            'Warehouse Penerimaan' => new \App\Exports\Sheets\WarehousePenerimaanSheet($this->data['warehouse']['penerimaan']),
            'Warehouse Retur' => new \App\Exports\Sheets\WarehouseReturSheet($this->data['warehouse']['retur']),
            'Warehouse Penyimpanan' => new \App\Exports\Sheets\WarehousePenyimpananSheet($this->data['warehouse']['penyimpanan']),
            'Warehouse Scrap' => new \App\Exports\Sheets\WarehouseScrapSheet($this->data['warehouse']['scrap']),
        ];
    }
}
