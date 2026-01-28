<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>চালান - {{ $invoice->invoice_no }}</title>
    <style>
        /* ==================== মূল ফন্ট ও বেসিক স্টাইল ==================== */
        @font-face {
            font-family: 'Nikosh';
            font-style: normal;
            font-weight: normal;
        }
        
        @font-face {
            font-family: 'Kalpurush';
            font-style: normal;
            font-weight: normal;
        }
        
        * {
            font-family: "Nikosh", "Kalpurush", "Siyam Rupali", sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            line-height: 1.5;
        }
        
        body {
            font-size: 13pt;
            color: #333333;
            background: #ffffff;
            margin: 0;
            padding: 0;
            direction: ltr;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* ==================== মূল কন্টেইনার ==================== */
        .invoice-wrapper {
            width: 100%;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 15mm;
            background: #ffffff;
            position: relative;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        
        /* ==================== হেডার সেকশন ==================== */
        .invoice-header {
            border-bottom: 2px solid #1a5276;
            padding-bottom: 15px;
            margin-bottom: 25px;
            position: relative;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .logo-section {
            flex: 1;
        }
        
        .logo-title {
            font-size: 24pt;
            color: #1a5276;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .logo-subtitle {
            font-size: 14pt;
            color: #2874a6;
            font-weight: normal;
        }
        
        .invoice-meta {
            text-align: right;
            flex: 1;
        }
        
        .invoice-title {
            font-size: 28pt;
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        .invoice-number {
            font-size: 16pt;
            color: #2c3e50;
            font-weight: bold;
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 5px;
            display: inline-block;
            border-left: 4px solid #3498db;
        }
        
        /* ==================== কোম্পানি তথ্য ==================== */
        .company-info {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 5px solid #2c3e50;
        }
        
        .company-info h3 {
            color: #2c3e50;
            font-size: 16pt;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #bdc3c7;
        }
        
        .company-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }
        
        .company-details .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .detail-label {
            font-weight: bold;
            color: #34495e;
            min-width: 100px;
        }
        
        .detail-value {
            color: #2c3e50;
        }
        
        /* ==================== গ্রাহক তথ্য ==================== */
        .client-info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .bill-to {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #3498db;
            box-shadow: 0 3px 10px rgba(52, 152, 219, 0.1);
        }
        
        .bill-to h3 {
            color: #3498db;
            font-size: 16pt;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #3498db;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .bill-to h3:before {
            content: "📋";
            font-size: 18pt;
        }
        
        .client-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .client-row {
            display: flex;
            align-items: flex-start;
            padding: 5px 0;
            border-bottom: 1px dashed #eee;
        }
        
        .client-label {
            font-weight: bold;
            color: #2c3e50;
            min-width: 120px;
        }
        
        .client-value {
            color: #000;
            flex: 1;
        }
        
        /* ==================== পরিমাণ সীমাবদ্ধকরণ ==================== */
        .amount-highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
            margin: 30px 0;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .amount-highlight:before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            transform: rotate(15deg);
            z-index: 0;
        }
        
        .amount-text {
            font-size: 16pt;
            color: rgba(255,255,255,0.9);
            margin-bottom: 10px;
        }
        
        .amount-value {
            font-size: 36pt;
            color: #ffffff;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        
        /* ==================== আইটেম টেবিল ==================== */
        .items-table-section {
            margin: 35px 0;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12pt;
        }
        
        .items-table thead {
            background: linear-gradient(to right, #2c3e50, #34495e);
        }
        
        .items-table th {
            color: white;
            padding: 16px 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13pt;
            border: none;
        }
        
        .items-table th:first-child {
            border-radius: 10px 0 0 0;
        }
        
        .items-table th:last-child {
            border-radius: 0 10px 0 0;
        }
        
        .items-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .items-table tbody tr:hover {
            background: #e3f2fd;
            transition: background 0.3s;
        }
        
        .serial-no {
            text-align: center;
            font-weight: bold;
            color: #2c3e50;
            font-size: 14pt;
        }
        
        .item-description {
            color: #2c3e50;
        }
        
        .item-amount {
            text-align: right;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .total-row {
            background: linear-gradient(to right, #e9ecef, #dee2e6) !important;
            font-weight: bold;
            font-size: 14pt;
        }
        
        .total-row td {
            padding: 18px 12px;
            color: #2c3e50;
        }
        
        .total-label {
            text-align: right;
            font-size: 14pt;
        }
        
        .total-amount {
            text-align: right;
            font-size: 16pt;
            color: #e74c3c;
        }
        
        /* ==================== পেমেন্ট স্ট্যাটাস ==================== */
        .payment-status-section {
            margin: 40px 0;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .status-paid {
            background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
            border: 3px solid #27ae60;
        }
        
        .status-unpaid {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            border: 3px solid #e74c3c;
        }
        
        .status-icon {
            font-size: 40pt;
            margin-bottom: 15px;
            display: block;
        }
        
        .status-text {
            font-size: 20pt;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }
        
        .status-details {
            font-size: 14pt;
            color: rgba(255,255,255,0.9);
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        
        /* ==================== টার্মস এন্ড কন্ডিশন ==================== */
        .terms-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            border-left: 4px solid #95a5a6;
        }
        
        .terms-title {
            color: #2c3e50;
            font-size: 14pt;
            margin-bottom: 12px;
            font-weight: bold;
        }
        
        .terms-list {
            list-style: none;
            padding-left: 0;
        }
        
        .terms-list li {
            padding: 5px 0;
            color: #555;
            position: relative;
            padding-left: 25px;
        }
        
        .terms-list li:before {
            content: "✓";
            color: #27ae60;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        /* ==================== ফুটার ==================== */
        .invoice-footer {
            margin-top: 50px;
            padding-top: 25px;
            border-top: 2px solid #bdc3c7;
            text-align: center;
        }
        
        .signature-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .signature-box {
            padding: 15px;
            text-align: center;
        }
        
        .signature-line {
            height: 1px;
            background: #000;
            margin: 40px 0 10px;
        }
        
        .signature-title {
            font-size: 12pt;
            color: #2c3e50;
            font-weight: bold;
        }
        
        .footer-note {
            font-size: 11pt;
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .copyright {
            font-size: 10pt;
            color: #95a5a6;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #bdc3c7;
        }
        
        /* ==================== ওয়াটারমার্ক ==================== */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120pt;
            color: rgba(0,0,0,0.03);
            z-index: -1;
            font-weight: bold;
            white-space: nowrap;
            pointer-events: none;
            font-family: "Nikosh", sans-serif;
        }
        
        /* ==================== ইউটিলিটি ক্লাস ==================== */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .bangla-number { font-family: "Nikosh", "Kalpurush", sans-serif; font-weight: bold; }
        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }
        .mb-30 { margin-bottom: 30px; }
        .mt-20 { margin-top: 20px; }
        .mt-30 { margin-top: 30px; }
        
        /* ==================== প্রিন্ট অপ্টিমাইজেশন ==================== */
        @media print {
            .invoice-wrapper {
                box-shadow: none;
                padding: 15mm 10mm;
                max-width: 100%;
            }
            
            .amount-highlight {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .status-paid {
                background: #00b09b !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .status-unpaid {
                background: #ff416c !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .watermark {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- ওয়াটারমার্ক -->
    <div class="watermark">{{ config('app.name', 'ইউনিয়ন ডিজিটাল') }}</div>
    
    <div class="invoice-wrapper">
        <!-- হেডার সেকশন -->
        <div class="invoice-header">
            <div class="header-top">
                <div class="logo-section">
                    <div class="logo-title">{{ config('app.name', 'ইউনিয়ন ডিজিটাল সেবা') }}</div>
                    <div class="logo-subtitle">সরকারি ডিজিটাল সেবা কেন্দ্র</div>
                </div>
                <div class="invoice-meta">
                    <div class="invoice-title">চালান</div>
                    <div class="invoice-number">নং: {{ $invoice->invoice_no }}</div>
                </div>
            </div>
            <div class="company-info">
                <h3>🔗 প্রতিষ্ঠান তথ্য</h3>
                <div class="company-details">
                    <div class="detail-item">
                        <span class="detail-label">ঠিকানা:</span>
                        <span class="detail-value">ইউনিয়ন পরিষদ ভবন, ডিজিটাল সেন্টার</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">ফোন:</span>
                        <span class="detail-value">০১৭XX-XXXXXX</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">ইমেইল:</span>
                        <span class="detail-value">info@union.gov.bd</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">ওয়েবসাইট:</span>
                        <span class="detail-value">www.union.gov.bd</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- গ্রাহক তথ্য -->
        <div class="client-info-section">
            <div class="bill-to">
                <h3>গ্রাহকের তথ্য</h3>
                <div class="client-details">
                    <div class="client-row">
                        <span class="client-label">নাম:</span>
                        <span class="client-value">{{ $invoice->user->name ?? 'নাম পাওয়া যায়নি' }}</span>
                    </div>
                    <div class="client-row">
                        <span class="client-label">মোবাইল:</span>
                        <span class="client-value">{{ $invoice->user->phone ?? 'নম্বর নেই' }}</span>
                    </div>
                    <div class="client-row">
                        <span class="client-label">ইমেইল:</span>
                        <span class="client-value">{{ $invoice->user->email ?? 'ইমেইল নেই' }}</span>
                    </div>
                    <div class="client-row">
                        <span class="client-label">সেবার ধরন:</span>
                        <span class="client-value">{{ $invoice->application->certificateType->name ?? 'সনদ সেবা' }}</span>
                    </div>
                    <div class="client-row">
                        <span class="client-label">আবেদন নং:</span>
                        <span class="client-value">{{ $invoice->application->application_no ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bill-to">
                <h3>চালান বিবরণ</h3>
                <div class="client-details">
                    <div class="client-row">
                        <span class="client-label">চালান নং:</span>
                        <span class="client-value">{{ $invoice->invoice_no }}</span>
                    </div>
                    <div class="client-row">
                        <span class="client-label">ইস্যু তারিখ:</span>
                        <span class="client-value">{{ banglaDate($invoice->created_at ?? now()) }}</span>
                    </div>
                    <div class="client-row">
                        <span class="client-label">মেয়াদ তারিখ:</span>
                        <span class="client-value">{{ banglaDate(($invoice->created_at ?? now())->addDays(30)) }}</span>
                    </div>
                    <div class="client-row">
                        <span class="client-label">রেফারেন্স:</span>
                        <span class="client-value">{{ $invoice->application->application_no ?? 'N/A' }}</span>
                    </div>
                    <div class="client-row">
                        <span class="client-label">পেমেন্ট মেথড:</span>
                        <span class="client-value">অনলাইন পেমেন্ট</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- মোট পরিমাণ হাইলাইট -->
        <div class="amount-highlight">
            <div class="amount-text">মোট প্রদেয়</div>
            <div class="amount-value bangla-number">{{ banglaMoney($totalAmount ?? ($invoice->amount + ($invoice->vat_amount ?? 0) + ($invoice->service_charge ?? 0))) }} ৳</div>
        </div>
        
        <!-- আইটেম টেবিল -->
        <div class="items-table-section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="60" class="text-center">ক্রঃ নং</th>
                        <th>সেবার বিবরণ</th>
                        <th width="150" class="text-right">পরিমাণ (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="serial-no bangla-number">১</td>
                        <td class="item-description">{{ $invoice->application->certificateType->name ?? 'সনদ ফি' }}</td>
                        <td class="item-amount bangla-number">{{ banglaMoney($invoice->amount) }}</td>
                    </tr>
                    
                    @if(($invoice->vat_amount ?? 0) > 0)
                    <tr>
                        <td class="serial-no bangla-number">২</td>
                        <td class="item-description">মূল্য সংযোজন কর (ভ্যাট)</td>
                        <td class="item-amount bangla-number">{{ banglaMoney($invoice->vat_amount) }}</td>
                    </tr>
                    @endif
                    
                    @if(($invoice->service_charge ?? 0) > 0)
                    <tr>
                        <td class="serial-no bangla-number">৩</td>
                        <td class="item-description">সেবা চার্জ</td>
                        <td class="item-amount bangla-number">{{ banglaMoney($invoice->service_charge) }}</td>
                    </tr>
                    @endif
                    
                    <!-- খালি সারি -->
                    <tr>
                        <td colspan="3" style="height: 20px;"></td>
                    </tr>
                    
                    <!-- মোট সারি -->
                    <tr class="total-row">
                        <td colspan="2" class="total-label">সর্বমোট</td>
                        <td class="total-amount bangla-number">
                            {{ banglaMoney($invoice->amount + ($invoice->vat_amount ?? 0) + ($invoice->service_charge ?? 0)) }} ৳
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- পেমেন্ট স্ট্যাটাস -->
        <div class="payment-status-section {{ $invoice->payment_status == 'paid' ? 'status-paid' : 'status-unpaid' }}">
            <span class="status-icon">{{ $invoice->payment_status == 'paid' ? '✅' : '⚠️' }}</span>
            <div class="status-text">
                পেমেন্ট অবস্থা: {{ $invoice->payment_status == 'paid' ? 'পরিশোধিত' : 'বকেয়া' }}
            </div>
            
            @if($invoice->payment_status == 'paid' && $invoice->paid_at)
            <div class="status-details">
                পরিশোধের তারিখ: {{ banglaDate($invoice->paid_at) }}<br>
                সময়: {{ $invoice->paid_at->format('h:i A') }}
            </div>
            @endif
            
            @if($invoice->payment_status != 'paid')
            <div class="status-details mt-20">
                দয়া করে ৩০ দিনের মধ্যে পরিশোধ করুন
            </div>
            @endif
        </div>
        
        <!-- টার্মস এন্ড কন্ডিশন -->
        <div class="terms-section">
            <div class="terms-title">📋 শর্তাবলী ও নীতিমালা</div>
            <ul class="terms-list">
                <li>এই চালানটি ডিজিটাল স্বাক্ষর যুক্ত এবং আইনগতভাবে বাধ্যতামূলক</li>
                <li>চালান জারির ৩০ দিনের মধ্যে পেমেন্ট করতে হবে</li>
                <li>বিলম্বের জন্য মাসিক ২% সুদ প্রযোজ্য</li>
                <li>যেকোনো বিরোধের ক্ষেত্রে ইউনিয়ন পরিষদের সিদ্ধান্ত চূড়ান্ত</li>
                <li>অর্থপ্রদান পরবর্তী রসিদ সংরক্ষণ করুন</li>
            </ul>
        </div>
        
        <!-- ফুটার -->
        <div class="invoice-footer">
            <!-- স্বাক্ষর সেকশন -->
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-title">গ্রাহকের স্বাক্ষর</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-title">ক্যাশিয়ারের স্বাক্ষর</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-title">প্রধান নির্বাহীর স্বাক্ষর</div>
                </div>
            </div>
            
            <!-- ফুটার নোট -->
            <div class="footer-note">
                <p>এই একটি কম্পিউটার জেনারেটেড চালান। কোন প্রকার কাটছাট বা পরিবর্তন গ্রহণযোগ্য নয়।</p>
                <p>আপনার মূল্যবান সময় দেওয়ার জন্য ধন্যবাদ। ডিজিটাল বাংলাদেশ গড়তে আপনার সহযোগিতা কাম্য।</p>
            </div>
            
            <!-- কপিরাইট -->
            <div class="copyright">
                © {{ banglaNumber(date('Y')) }} {{ config('app.name', 'ইউনিয়ন ডিজিটাল ব্যবস্থাপনা') }} | 
                সকল অধিকার সংরক্ষিত | 
                Printed on: {{ banglaDate(now()) }} at {{ now()->format('h:i A') }}
            </div>
        </div>
    </div>
</body>
</html>

@php
    // ==================== সম্পূর্ণ নতুন সিস্টেম ====================
    
    // ম্যানুয়ালি প্রতিটি ডিজিট কনভার্ট করার ফাংশন
    function convertToBanglaDigit($char) {
        switch ($char) {
            case '0': return '০';
            case '1': return '১';
            case '2': return '২';
            case '3': return '৩';
            case '4': return '৪';
            case '5': return '৫';
            case '6': return '৬';
            case '7': return '৭';
            case '8': return '৮';
            case '9': return '৯';
            default: return $char;
        }
    }
    
    // সম্পূর্ণ স্ট্রিং কনভার্ট করার ফাংশন
    function convertNumberToBangla($number) {
        $str = (string) $number;
        $result = '';
        
        for ($i = 0; $i < strlen($str); $i++) {
            $result .= convertToBanglaDigit($str[$i]);
        }
        
        return $result;
    }
    
    // তারিখের জন্য বিশেষ ফাংশন
    function getBanglaDate($date) {
        if (!$date) return 'তারিখ নেই';
        
        $banglaMonths = [
            1 => 'জানুয়ারি',
            2 => 'ফেব্রুয়ারি',
            3 => 'মার্চ',
            4 => 'এপ্রিল',
            5 => 'মে',
            6 => 'জুন',
            7 => 'জুলাই',
            8 => 'আগস্ট',
            9 => 'সেপ্টেম্বর',
            10 => 'অক্টোবর',
            11 => 'নভেম্বর',
            12 => 'ডিসেম্বর'
        ];
        
        try {
            if (is_string($date)) {
                $date = \Carbon\Carbon::parse($date);
            }
            
            $day = $date->day;
            $month = $date->month;
            $year = $date->year;
            
            // শুধু দিন এবং বছর কনভার্ট করুন
            $banglaDay = convertNumberToBangla($day);
            $banglaYear = convertNumberToBangla($year);
            
            return $banglaDay . ' ' . ($banglaMonths[$month] ?? '') . ' ' . $banglaYear;
            
        } catch (\Exception $e) {
            return 'তারিখ নেই';
        }
    }
    
    // টাকার জন্য ফাংশন
    function getBanglaMoney($amount) {
        $amount = floatval($amount);
        
        // দুই দশমিক স্থান পর্যন্ত
        $formatted = number_format($amount, 2, '.', '');
        
        // প্রতিটি ক্যারেক্টার কনভার্ট করুন
        $result = '';
        for ($i = 0; $i < strlen($formatted); $i++) {
            $result .= convertToBanglaDigit($formatted[$i]);
        }
        
        return $result;
    }
    
    // কমা সেপারেটর সহ টাকা
    function getBanglaMoneyWithComma($amount) {
        $amount = floatval($amount);
        
        if ($amount >= 1000) {
            $formatted = number_format($amount, 2);
        } else {
            $formatted = number_format($amount, 2, '.', '');
        }
        
        $result = '';
        for ($i = 0; $i < strlen($formatted); $i++) {
            $result .= convertToBanglaDigit($formatted[$i]);
        }
        
        return $result;
    }
    
    // ব্লেডের জন্য গ্লোবাল ফাংশন তৈরি করুন
    if (!function_exists('banglaDate')) {
        function banglaDate($date) {
            return getBanglaDate($date);
        }
    }
    
    if (!function_exists('banglaNumber')) {
        function banglaNumber($number) {
            return convertNumberToBangla(intval($number));
        }
    }
    
    if (!function_exists('banglaMoney')) {
        function banglaMoney($amount) {
            return getBanglaMoney($amount);
        }
    }
    
    if (!function_exists('banglaMoneyFormatted')) {
        function banglaMoneyFormatted($amount) {
            return getBanglaMoneyWithComma($amount);
        }
    }
    
    // টোটাল ক্যালকুলেশন
    $totalAmount = $invoice->amount + ($invoice->vat_amount ?? 0) + ($invoice->service_charge ?? 0);
@endphp