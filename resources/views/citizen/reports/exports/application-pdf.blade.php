<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Report - {{ ucfirst($period) }}</title>
    <style>
        @page {
            margin: 20px;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #10B981;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #10B981;
            margin-bottom: 10px;
        }
        
        .header .subtitle {
            font-size: 16px;
            color: #6B7280;
        }
        
        .info-section {
            margin-bottom: 20px;
            background: #F3F4F6;
            padding: 15px;
            border-radius: 8px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 6px;
            border: 1px solid #E5E7EB;
        }
        
        .info-label {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }
        
        .status-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .status-card {
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .status-pending {
            background: #FEF3C7;
            border: 1px solid #F59E0B;
            color: #92400E;
        }
        
        .status-approved {
            background: #D1FAE5;
            border: 1px solid #10B981;
            color: #065F46;
        }
        
        .status-rejected {
            background: #FEE2E2;
            border: 1px solid #EF4444;
            color: #991B1B;
        }
        
        .status-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .status-count {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .status-percentage {
            font-size: 12px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 11px;
            color: #6B7280;
        }
        
        .period-info {
            background: #D1FAE5;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #10B981;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mb-20 {
            margin-bottom: 20px;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Application Report</h1>
        <div class="subtitle">Period: {{ ucfirst($period) }} Report</div>
        <div>Generated on: {{ now()->format('F j, Y h:i A') }}</div>
    </div>
    
    <div class="period-info">
        <strong>Report Period:</strong> {{ $data['period'] ?? ucfirst($period) }}<br>
        <strong>Report Date:</strong> {{ $data['date'] ?? now()->format('Y-m-d') }}
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Total Applications</div>
            <div class="info-value">{{ $data['total'] ?? 0 }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Pending</div>
            <div class="info-value">{{ $data['pending'] ?? 0 }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Approved</div>
            <div class="info-value">{{ $data['approved'] ?? 0 }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Rejected</div>
            <div class="info-value">{{ $data['rejected'] ?? 0 }}</div>
        </div>
    </div>
    
    @if($data['total'] > 0)
    <div class="status-grid">
        <div class="status-card status-pending">
            <div class="status-title">Pending</div>
            <div class="status-count">{{ $data['pending'] }}</div>
            <div class="status-percentage">{{ round(($data['pending'] / $data['total']) * 100, 1) }}% of total</div>
        </div>
        
        <div class="status-card status-approved">
            <div class="status-title">Approved</div>
            <div class="status-count">{{ $data['approved'] }}</div>
            <div class="status-percentage">{{ round(($data['approved'] / $data['total']) * 100, 1) }}% of total</div>
        </div>
        
        <div class="status-card status-rejected">
            <div class="status-title">Rejected</div>
            <div class="status-count">{{ $data['rejected'] }}</div>
            <div class="status-percentage">{{ round(($data['rejected'] / $data['total']) * 100, 1) }}% of total</div>
        </div>
    </div>
    @endif
    
    <div class="info-section">
        <strong>Report Summary:</strong><br>
        This report shows application statistics for the selected period. The data includes all certificate 
        applications submitted during this time frame, categorized by their current status.
    </div>
    
    @if(isset($data['previous']['total']) && $data['previous']['total'] > 0)
        <div class="info-section">
            @php
                $change = (($data['total'] - $data['previous']['total']) / $data['previous']['total']) * 100;
            @endphp
            <strong>Period Comparison:</strong><br>
            @if($change > 0)
                Total applications increased by {{ round($change, 1) }}% compared to the previous period.<br>
            @else
                Total applications decreased by {{ round(abs($change), 1) }}% compared to the previous period.<br>
            @endif
            Previous period applications: {{ $data['previous']['total'] }}
        </div>
    @endif
    
    <div class="footer">
        <div>Report generated by Union Management System</div>
        <div>This is an official system generated report</div>
        <div>Page 1 of 1</div>
    </div>
</body>
</html>