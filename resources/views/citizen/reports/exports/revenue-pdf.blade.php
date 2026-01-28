<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Revenue Report - {{ ucfirst($period) }}</title>
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
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #4F46E5;
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
            grid-template-columns: repeat(3, 1fr);
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
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .table th {
            background: #4F46E5;
            color: white;
            text-align: left;
            padding: 10px;
            font-weight: 600;
        }
        
        .table td {
            padding: 10px;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .table tr:nth-child(even) {
            background: #F9FAFB;
        }
        
        .table tr:hover {
            background: #F3F4F6;
        }
        
        .total-row {
            font-weight: bold;
            background: #ECFDF5 !important;
            color: #065F46;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 11px;
            color: #6B7280;
        }
        
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .badge-paid {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .badge-pending {
            background: #FEF3C7;
            color: #92400E;
        }
        
        .period-info {
            background: #EEF2FF;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #4F46E5;
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
        <h1>Revenue Report</h1>
        <div class="subtitle">Period: {{ ucfirst($period) }} Report</div>
        <div>Generated on: {{ now()->format('F j, Y h:i A') }}</div>
    </div>
    
    <div class="period-info">
        <strong>Report Period:</strong> {{ $data['period'] ?? ucfirst($period) }}<br>
        <strong>Report Date:</strong> {{ $data['date'] ?? now()->format('Y-m-d') }}
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Total Revenue</div>
            <div class="info-value">৳ {{ number_format($data['total'] ?? 0, 2) }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Total Payments</div>
            <div class="info-value">{{ $data['count'] ?? 0 }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Avg. Transaction</div>
            <div class="info-value">৳ {{ $data['count'] > 0 ? number_format($data['total'] / $data['count'], 2) : '0.00' }}</div>
        </div>
    </div>
    
    <div class="info-section">
        <strong>Report Summary:</strong><br>
        This report shows revenue data for the selected period. The total revenue includes all successful payments 
        for certificate applications. The data represents actual funds received during this period.
    </div>
    
    @if(isset($data['previous']) && $data['previous'] > 0)
        <div class="info-section">
            @php
                $change = (($data['total'] - $data['previous']) / $data['previous']) * 100;
            @endphp
            <strong>Period Comparison:</strong><br>
            @if($change > 0)
                Revenue increased by {{ round($change, 1) }}% compared to the previous period.
            @else
                Revenue decreased by {{ round(abs($change), 1) }}% compared to the previous period.
            @endif
            Previous period revenue: ৳ {{ number_format($data['previous'], 2) }}
        </div>
    @endif
    
    <div class="footer">
        <div>Report generated by Union Management System</div>
        <div>This is an official system generated report</div>
        <div>Page 1 of 1</div>
    </div>
</body>
</html>