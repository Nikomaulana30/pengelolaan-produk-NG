<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Warehouse Recap Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            font-size: 10px; 
            line-height: 1.1; 
            color: #333;
            background: white;
        }
        .page { page-break-after: always; padding: 12px 18px; }
        .header { 
            text-align: center; 
            margin-bottom: 10px; 
            border-bottom: 3px solid #43e97b; 
            padding-bottom: 8px;
        }
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px; }
        .logo { font-weight: bold; font-size: 12px; color: #333; }
        .title { font-size: 14px; font-weight: bold; color: #43e97b; }
        .header-info { font-size: 8px; color: #666; margin-top: 2px; }
        .header-info p { margin: 0px 0; line-height: 1.1; }
        
        .stats-container { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 4px; 
            margin-bottom: 5px;
            height: 0;
            overflow: hidden;
            visibility: hidden;
        }
        .stat-card { 
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            padding: 6px; 
            border-radius: 4px;
            color: white;
            text-align: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .stat-value { font-size: 15px; font-weight: bold; display: block; line-height: 1.1; }
        .stat-label { font-size: 5px; margin-top: 0px; opacity: 0.9; }
        
        .section-title { 
            background: #43e97b; 
            color: white;
            padding: 5px 10px; 
            margin: 4px 0 0px 0; 
            font-weight: bold; 
            border-radius: 3px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            line-height: 1.1;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 5px;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
        }
        th { 
            background: #43e97b; 
            color: white;
            padding: 5px 6px; 
            text-align: left; 
            font-weight: bold; 
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            line-height: 1.1;
        }
        td { 
            padding: 4px 6px; 
            border-bottom: 1px solid #eee;
            font-size: 8px;
            line-height: 1.1;
        }
        tr:nth-child(even) { background: #f9f9f9; }
        tr:hover { background: #f0f8ff; }
        
        .badge { 
            display: inline-block; 
            padding: 2px 5px; 
            border-radius: 10px; 
            font-size: 7px; 
            font-weight: bold; 
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.1px;
            line-height: 1;
        }
        .badge-success { background: #28a745; }
        .badge-warning { background: #ff9800; color: white; }
        .badge-danger { background: #dc3545; }
        .badge-primary { background: #43e97b; }
        .badge-approved { background: #28a745; }
        .badge-pending { background: #ffc107; color: #333; }
        .badge-rejected { background: #dc3545; }
        
        .empty-state { 
            text-align: center; 
            padding: 20px; 
            color: #999;
            font-style: italic;
            background: #f5f5f5;
            border-radius: 4px;
        }
        
        .footer { 
            margin-top: 10px; 
            text-align: center; 
            font-size: 7px; 
            color: #999; 
            border-top: 1px solid #ddd; 
            padding-top: 5px;
            line-height: 1.2;
        }
        
        .summary-row {
            background: #f5f5f5;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="logo">METINCA</div>
                <div class="title">WAREHOUSE RECAP REPORT</div>
                <div style="font-size: 12px; color: #43e97b; font-weight: bold;">WH</div>
            </div>
            <div class="header-info">
                <p><strong>Period:</strong> {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
                <p><strong>Generated:</strong> {{ now()->format('d M Y H:i:s') }} | <strong>Document:</strong> WH-{{ now()->format('Ymd-His') }}</p>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="stats-container">
            <div class="stat-card">
                <span class="stat-value">{{ $penerimaanBarang->count() }}</span>
                <span class="stat-label">RECEIPTS</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $returBarang->count() }}</span>
                <span class="stat-label">RETURNS</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $penyimpanan->count() }}</span>
                <span class="stat-label">STORAGE</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $scrapDisposal->count() }}</span>
                <span class="stat-label">SCRAP</span>
            </div>
        </div>

        <!-- Goods Receipt Section -->
        <div class="section-title">Goods Receipt</div>
        @if($penerimaanBarang->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 35%;">Product</th>
                    <th style="width: 15%; text-align: right;">Qty Good</th>
                    <th style="width: 18%; text-align: right;">Qty Damaged</th>
                    <th style="width: 20%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penerimaanBarang->take(8) as $item)
                <tr>
                    <td>{{ $item->tanggal_input?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $item->nama_barang ?? '-' }}</td>
                    <td style="text-align: right;">{{ $item->qty_baik ?? 0 }}</td>
                    <td style="text-align: right;">{{ $item->qty_rusak ?? 0 }}</td>
                    <td><span class="badge badge-{{ strtolower($item->status ?? 'draft') }}">{{ ucfirst($item->status ?? 'draft') }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">No receipt data available for this period</div>
        @endif

        <!-- Returned Goods Section -->
        <div class="section-title">Returned Goods</div>
        @if($returBarang->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 30%;">Product</th>
                    <th style="width: 12%; text-align: center;">Qty</th>
                    <th style="width: 20%;">Reason</th>
                    <th style="width: 16%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returBarang->take(8) as $item)
                <tr>
                    <td>{{ $item->tanggal_retur?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                    <td style="text-align: center;">{{ $item->jumlah_retur ?? 0 }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($item->alasan_retur ?? '-')) }}</td>
                    <td><span class="badge badge-{{ $item->status_approval === 'approved' ? 'success' : ($item->status_approval === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($item->status_approval ?? '-') }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">No return data available for this period</div>
        @endif

        <!-- NG Storage Section -->
        <div class="section-title">NG Storage</div>
        @if($penyimpanan->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 30%;">Product</th>
                    <th style="width: 15%; text-align: right;">Initial Qty</th>
                    <th style="width: 15%;">Zone</th>
                    <th style="width: 18%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penyimpanan->take(8) as $item)
                <tr>
                    <td>{{ $item->tanggal_penyimpanan?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $item->nama_barang ?? '-' }}</td>
                    <td style="text-align: right;">{{ $item->qty_awal ?? 0 }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($item->zone ?? '-')) }}</td>
                    <td><span class="badge badge-{{ $item->status_barang === 'disimpan' ? 'warning' : 'success' }}">{{ ucfirst(str_replace('_', ' ', $item->status_barang ?? '-')) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">No storage data available for this period</div>
        @endif

        <!-- Scrap/Disposal Section -->
        <div class="section-title">Scrap/Disposal</div>
        @if($scrapDisposal->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 30%;">Product</th>
                    <th style="width: 15%; text-align: right;">Quantity</th>
                    <th style="width: 20%;">Method</th>
                    <th style="width: 13%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scrapDisposal->take(8) as $item)
                <tr>
                    <td>{{ $item->tanggal_scrap?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $item->nama_barang ?? '-' }}</td>
                    <td style="text-align: right;">{{ $item->jumlah_barang ?? 0 }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($item->metode_disposal ?? '-')) }}</td>
                    <td><span class="badge badge-{{ $item->status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($item->status ?? 'draft') }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">No scrap data available for this period</div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This is an official document generated by Metinca Warehouse Management System</p>
            <p>© {{ now()->year }} Metinca - All Rights Reserved</p>
            <p style="margin-top: 5px; font-size: 7px;">Document ID: {{ md5($startDate . $endDate . 'warehouse') }}</p>
        </div>
    </div>
</body>
</html>

    <!-- Penerimaan Barang -->
    <div class="section-title">Goods Receipt</div>
    @if($penerimaanBarang->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Qty Good</th>
                <th>Qty Damaged</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penerimaanBarang->take(5) as $item)
            <tr>
                <td>{{ $item->tanggal_input?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $item->nama_barang ?? '-' }}</td>
                <td style="text-align: right;">{{ $item->qty_baik ?? 0 }}</td>
                <td style="text-align: right;">{{ $item->qty_rusak ?? 0 }}</td>
                <td><span class="badge badge-success">{{ ucfirst($item->status ?? 'draft') }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; color: #999; padding: 10px;">No receipt data available</p>
    @endif

    <!-- Retur Barang -->
    <div class="section-title">Returned Goods</div>
    @if($returBarang->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returBarang->take(5) as $item)
            <tr>
                <td>{{ $item->tanggal_retur?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                <td style="text-align: right;">{{ $item->jumlah_retur ?? 0 }}</td>
                <td>{{ str_replace('_', ' ', ucfirst($item->alasan_retur ?? '-')) }}</td>
                <td><span class="badge badge-{{ $item->status_approval === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($item->status_approval ?? '-') }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; color: #999; padding: 10px;">No return data available</p>
    @endif

    <!-- Penyimpanan NG -->
    <div class="section-title">NG Storage</div>
    @if($penyimpanan->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Qty Initial</th>
                <th>Zone</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penyimpanan->take(5) as $item)
            <tr>
                <td>{{ $item->tanggal_penyimpanan?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $item->nama_barang ?? '-' }}</td>
                <td style="text-align: right;">{{ $item->qty_awal ?? 0 }}</td>
                <td>{{ str_replace('_', ' ', ucfirst($item->zone ?? '-')) }}</td>
                <td><span class="badge badge-{{ $item->status_barang === 'disimpan' ? 'warning' : 'success' }}">{{ ucfirst(str_replace('_', ' ', $item->status_barang ?? '-')) }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; color: #999; padding: 10px;">No storage data available</p>
    @endif

    <!-- Scrap Disposal -->
    <div class="section-title">Scrap/Disposal</div>
    @if($scrapDisposal->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scrapDisposal->take(5) as $item)
            <tr>
                <td>{{ $item->tanggal_scrap?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $item->nama_barang ?? '-' }}</td>
                <td style="text-align: right;">{{ $item->jumlah_barang ?? 0 }}</td>
                <td>{{ str_replace('_', ' ', ucfirst($item->metode_disposal ?? '-')) }}</td>
                <td><span class="badge badge-{{ $item->status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($item->status ?? 'draft') }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; color: #999; padding: 10px;">No scrap data available</p>
    @endif

    <div class="footer">
        <p>This is an automated report generated by Metinca System</p>
        <p>© {{ now()->year }} - All Rights Reserved</p>
    </div>
</body>
</html>
