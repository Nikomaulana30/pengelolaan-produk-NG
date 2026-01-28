<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PPIC Recap Report</title>
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
            border-bottom: 3px solid #667eea; 
            padding-bottom: 15px;
        }
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .logo { font-weight: bold; font-size: 14px; color: #333; }
        .title { font-size: 16px; font-weight: bold; color: #667eea; }
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 12px; 
            border-radius: 6px;
            color: white;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-value { font-size: 20px; font-weight: bold; display: block; }
        .stat-label { font-size: 8px; margin-top: 3px; opacity: 0.9; }
        
        .section-title { 
            background: #667eea; 
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
            background: #667eea; 
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
        tr:hover { background: #f0f0ff; }
        
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
        .badge-success { background: #28a745; }
        .badge-warning { background: #ff9800; }
        .badge-danger { background: #dc3545; }
        .badge-primary { background: #667eea; }
        .badge-approved { background: #28a745; }
        .badge-pending { background: #ffc107; color: #333; }
        .badge-rejected { background: #dc3545; }
        .badge-closed { background: #28a745; }
        .badge-open { background: #ffc107; color: #333; }
        
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
                <div class="title">PPIC RECAP REPORT</div>
                <div style="font-size: 12px; color: #667eea; font-weight: bold;">PPIC</div>
            </div>
            <div class="header-info">
                <p><strong>Period:</strong> {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
                <p><strong>Generated:</strong> {{ now()->format('d M Y H:i:s') }} | <strong>Document:</strong> PPIC-{{ now()->format('Ymd-His') }}</p>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="stats-container">
            <div class="stat-card">
                <span class="stat-value">{{ $rcaAnalysis->count() }}</span>
                <span class="stat-label">RCA ANALYSIS</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $rcaAnalysis->where('status_rca', 'closed')->count() }}</span>
                <span class="stat-label">CLOSED</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $financeApprovals->count() }}</span>
                <span class="stat-label">FINANCE REQ</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $financeApprovals->where('status_approval', 'approved')->count() }}</span>
                <span class="stat-label">APPROVED</span>
            </div>
        </div>

        <!-- RCA Analysis Section -->
        <div class="section-title">📊 RCA Analysis</div>
        @if($rcaAnalysis->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">No. RCA</th>
                    <th style="width: 28%;">Root Cause</th>
                    <th style="width: 18%;">Description</th>
                    <th style="width: 18%;">Action Item</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rcaAnalysis->take(10) as $rca)
                <tr>
                    <td><strong>{{ $rca->nomor_rca ?? '-' }}</strong></td>
                    <td>{{ $rca->penyebab_utama ?? '-' }}</td>
                    <td>{{ Str::limit($rca->deskripsi_masalah ?? '-', 30) }}</td>
                    <td>{{ Str::limit($rca->action_item ?? '-', 25) }}</td>
                    <td><span class="badge badge-{{ strtolower($rca->status_rca ?? 'open') }}">{{ ucfirst($rca->status_rca ?? 'open') }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">No RCA data available for this period</div>
        @endif

        <!-- Finance Approval Section -->
        <div class="section-title">💰 Finance Approval Requests</div>
        @if($financeApprovals->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 25%;">Description</th>
                    <th style="width: 18%; text-align: right;">Amount</th>
                    <th style="width: 18%;">Approval</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($financeApprovals->take(10) as $finance)
                <tr>
                    <td>{{ $finance->tanggal_permohonan?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $finance->deskripsi ?? '-' }}</td>
                    <td style="text-align: right;">Rp {{ number_format($finance->nominal_permohonan ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $finance->tipe_persetujuan ?? '-' }}</td>
                    <td><span class="badge badge-{{ strtolower($finance->status_approval ?? 'pending') }}">{{ ucfirst($finance->status_approval ?? 'pending') }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">No finance approval data available for this period</div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This is an official document generated by Metinca PPIC Management System</p>
            <p>© {{ now()->year }} Metinca - All Rights Reserved</p>
            <p style="margin-top: 5px; font-size: 7px;">Document ID: {{ md5($startDate . $endDate . 'ppic') }}</p>
        </div>
    </div>
</body>
</html>
