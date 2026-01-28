<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>QA Recap Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            font-size: 10px; 
            line-height: 1.5; 
            color: #333;
        }
        .page { page-break-after: always; padding: 20px; }
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 3px solid #f093fb; 
            padding-bottom: 15px;
        }
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .logo { font-weight: bold; font-size: 14px; color: #333; }
        .title { font-size: 16px; font-weight: bold; color: #f093fb; }
        .header-info { font-size: 9px; color: #666; margin-top: 8px; }
        .header-info p { margin: 2px 0; }
        
        .stats-container { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 8px; 
            margin-bottom: 20px;
            height: 0;
            overflow: hidden;
            visibility: hidden;
        }
        .stat-card { 
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 12px; 
            border-radius: 6px;
            color: white;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-value { font-size: 20px; font-weight: bold; display: block; }
        .stat-label { font-size: 8px; margin-top: 3px; opacity: 0.9; }
        
        .section-title { 
            background: #f093fb; 
            color: white;
            padding: 8px 12px; 
            margin: 20px 0 10px 0; 
            font-weight: bold; 
            border-radius: 4px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 12px;
            background: white;
            border: 1px solid #ddd;
        }
        th { 
            background: #f093fb; 
            color: white;
            padding: 8px 10px; 
            text-align: left; 
            font-weight: bold; 
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        td { 
            padding: 7px 10px; 
            border-bottom: 1px solid #eee;
            font-size: 9px;
        }
        tr:nth-child(even) { background: #f9f9f9; }
        tr:hover { background: #fff0f8; }
        
        .badge { 
            display: inline-block; 
            padding: 3px 7px; 
            border-radius: 12px; 
            font-size: 8px; 
            font-weight: bold; 
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-ok { background: #28a745; }
        .badge-ng { background: #dc3545; }
        .badge-rework { background: #ff9800; }
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
            margin-top: 30px; 
            text-align: center; 
            font-size: 8px; 
            color: #999; 
            border-top: 1px solid #ddd; 
            padding-top: 12px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="logo">🏭 METINCA</div>
                <div class="title">QA RECAP REPORT</div>
                <div style="font-size: 12px; color: #f093fb; font-weight: bold;">QA</div>
            </div>
            <div class="header-info">
                <p><strong>Period:</strong> {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
                <p><strong>Generated:</strong> {{ now()->format('d M Y H:i:s') }} | <strong>Document:</strong> QA-{{ now()->format('Ymd-His') }}</p>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="stats-container">
            <div class="stat-card">
                <span class="stat-value">{{ $qualityInspections->count() }}</span>
                <span class="stat-label">TOTAL INSPECTIONS</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $qualityInspections->where('hasil', 'OK')->count() }}</span>
                <span class="stat-label">PASSED (OK)</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $qualityInspections->where('hasil', 'NG')->count() }}</span>
                <span class="stat-label">REJECTED (NG)</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $qualityApprovals->where('status_approval', 'approved')->count() }}</span>
                <span class="stat-label">APPROVED</span>
            </div>
        </div>

        <!-- Quality Inspection Section -->
        <div class="section-title">✅ Quality Inspections</div>
        @if($qualityInspections->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Date</th>
                    <th style="width: 28%;">Product</th>
                    <th style="width: 12%; text-align: center;">Qty</th>
                    <th style="width: 12%; text-align: center;">Result</th>
                    <th style="width: 18%;">Defects</th>
                    <th style="width: 12%;">Approval</th>
                    <th style="width: 8%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($qualityInspections->take(12) as $inspection)
                <tr>
                    <td>{{ $inspection->tanggal_inspeksi?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $inspection->produk->nama_produk ?? '-' }}</td>
                    <td style="text-align: center;">{{ $inspection->jumlah_produk ?? 0 }}</td>
                    <td style="text-align: center;"><span class="badge badge-{{ strtolower($inspection->hasil ?? 'pending') }}">{{ strtoupper($inspection->hasil ?? '-') }}</span></td>
                    <td>{{ Str::limit($inspection->jenis_cacat ?? '-', 20) }}</td>
                    <td>{{ ucfirst($inspection->status_approval ?? 'pending') }}</td>
                    <td><span class="badge badge-{{ strtolower($inspection->status ?? 'draft') }}">{{ ucfirst($inspection->status ?? 'draft') }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">No quality inspection data available for this period</div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This is an official document generated by Metinca Quality Assurance Management System</p>
            <p>© {{ now()->year }} Metinca - All Rights Reserved</p>
            <p style="margin-top: 5px; font-size: 7px;">Document ID: {{ md5($startDate . $endDate . 'qa') }}</p>
        </div>
    </div>
</body>
</html>
