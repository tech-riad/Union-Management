<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>নাগরিকত্ব সনদ - {{ $application->certificate_number }}</title>
    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font-size: 12pt;
            line-height: 1.5;
        }
        
        .certificate {
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            border: 15px solid #8B4513;
            box-sizing: border-box;
        }
        
        /* QR Code Section - Top Right */
        .qr-container {
            position: absolute;
            top: 30px;
            right: 30px;
            width: 120px;
            text-align: center;
            z-index: 100;
        }
        
        .qr-code {
            width: 100px !important;
            height: 100px !important;
            border: 1px solid #ddd;
            padding: 5px;
            background: white;
            display: block;
            margin: 0 auto;
        }
        
        .qr-text {
            font-size: 8pt;
            color: #333;
            margin-top: 5px;
            line-height: 1.2;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px double #000;
        }
        
        .header h1 {
            font-size: 24pt;
            color: #000;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 14pt;
            color: #333;
            margin-bottom: 3px;
        }
        
        .header h3 {
            font-size: 12pt;
            color: #555;
            font-weight: normal;
        }
        
        /* Certificate Number */
        .cert-number {
            position: absolute;
            top: 30px;
            left: 30px;
            background: #2c5282;
            color: white;
            padding: 8px 12px;
            font-size: 10pt;
            font-weight: bold;
            border-radius: 3px;
        }
        
        /* Content */
        .content {
            margin: 40px 20px 20px 20px;
        }
        
        .intro-text {
            text-align: center;
            font-size: 13pt;
            margin-bottom: 25px;
            font-weight: bold;
        }
        
        /* Applicant Info */
        .applicant-info {
            border: 2px solid #000;
            padding: 20px;
            margin: 20px 0;
            background-color: #f9f9f9;
        }
        
        .info-row {
            margin-bottom: 8px;
        }
        
        .info-label {
            display: inline-block;
            width: 180px;
            font-weight: bold;
            color: #2c5282;
        }
        
        .info-value {
            display: inline-block;
            color: #000;
        }
        
        .highlight {
            font-weight: bold;
            background-color: #fffacd;
            padding: 2px 4px;
        }
        
        /* Main Text */
        .main-text {
            text-align: justify;
            margin: 25px 0;
            font-size: 12pt;
        }
        
        .conclusion {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            color: #2c5282;
            margin: 30px 0;
        }
        
        /* Stamp */
        .stamp {
            position: absolute;
            bottom: 180px;
            right: 60px;
            width: 100px;
            height: 100px;
            border: 3px solid red;
            border-radius: 50%;
            text-align: center;
            display: table;
            background: white;
            opacity: 0.9;
        }
        
        .stamp-text {
            display: table-cell;
            vertical-align: middle;
            font-weight: bold;
            color: red;
            font-size: 10pt;
            line-height: 1.2;
        }
        
        /* Signatures */
        .signatures {
            position: absolute;
            bottom: 80px;
            left: 0;
            right: 0;
            display: table;
            width: 100%;
            padding: 0 40px;
        }
        
        .signature-box {
            display: table-cell;
            width: 45%;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-line {
            width: 150px;
            height: 1px;
            background: #000;
            margin: 5px auto;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }
        
        .signature-title {
            font-size: 11pt;
            color: #555;
        }
        
        .signature-office {
            font-size: 10pt;
            color: #666;
        }
        
        /* Footer */
        .footer-details {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #555;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
            margin: 0 40px;
        }
        
        .details-row {
            display: table;
            width: 100%;
        }
        
        .detail-item {
            display: table-cell;
            width: 33%;
            text-align: center;
        }
        
        /* Security Note */
        .security-note {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7pt;
            color: #ff0000;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="certificate">
        
        <!-- QR Code - TOP RIGHT (SVG compatible) -->
        <div class="qr-container">
            <div style="font-size: 9pt; font-weight: bold; color: #2c5282; margin-bottom: 3px;">
                যাচাই করুন
            </div>
            <!-- IMPORTANT: Direct img tag for SVG -->
            <img src="{{ $qrCode }}" alt="QR Code" class="qr-code" />
            <div class="qr-text">
                QR কোড স্ক্যান করে<br>
                সনদ যাচাই করুন<br>
                <span style="font-size: 7pt; color: #666;">
                    {{ $application->certificate_number }}
                </span>
            </div>
        </div>
        
        <!-- Certificate Number -->
        <div class="cert-number">
            সনদ নং: {{ $application->certificate_number }}
        </div>
        
        <!-- Header -->
        <div class="header">
            <h1>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</h1>
            <h2>স্থানীয় সরকার বিভাগ</h2>
            <h3>{{ $unionName ?? 'ইউনিয়ন পরিষদ' }}</h3>
            <h2 style="color: #b30000; margin-top: 10px;">নাগরিকত্ব সনদ</h2>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p class="intro-text">এই মর্মে প্রত্যয়ন করা যাচ্ছে যে,</p>
            
            <div class="applicant-info">
                <div class="info-row">
                    <span class="info-label">শ্রী/শ্রীমতি:</span>
                    <span class="info-value highlight">{{ $applicant->name_bangla ?? $formData['name_bangla'] ?? 'নাম' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">পিতার নাম:</span>
                    <span class="info-value">{{ $applicant->father_name_bangla ?? $formData['father_name_bangla'] ?? 'পিতার নাম' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">মাতার নাম:</span>
                    <span class="info-value">{{ $applicant->mother_name_bangla ?? $formData['mother_name_bangla'] ?? 'মাতার নাম' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">স্থায়ী ঠিকানা:</span>
                    <span class="info-value">{{ $applicant->permanent_address_bangla ?? $formData['permanent_address_bangla'] ?? 'ঠিকানা' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">জাতীয় পরিচয়পত্র নম্বর:</span>
                    <span class="info-value highlight">{{ $applicant->nid_number ?? $formData['nid_number'] ?? 'N/A' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">জন্ম তারিখ:</span>
                    <span class="info-value">{{ $applicant->dob ?? $formData['date_of_birth'] ?? 'N/A' }}</span>
                </div>
            </div>
            
            <p class="main-text">
                উক্ত ব্যক্তি এই ইউনিয়নের একজন আইনানুগ নাগরিক এবং স্থায়ী বাসিন্দা হিসেবে নিবন্ধিত আছেন। 
                তার সম্পর্কে এই পরিষদের রেকর্ডে কোন অভিযোগ বা ফৌজদারী মামলা নেই। 
                তিনি এই ইউনিয়নের একজন সুনাগরিক হিসেবে পরিচিত এবং বাংলাদেশের একজন স্থায়ী বাসিন্দা হিসেবে 
                সরকার কর্তৃক প্রদত্ত সকল নাগরিক সুবিধা পাওয়ার অধিকারী।
            </p>
            
            <p class="conclusion">
                অতএব, বাংলাদেশের আইন অনুসারে তাকে নাগরিকত্ব সনদ প্রদান করা হলো।
            </p>
        </div>
        
        <!-- Official Stamp -->
        <div class="stamp">
            <div class="stamp-text">
                সীলমোহর<br>
                ইউনিয়ন পরিষদ<br>
                {{ $unionName ?? 'ইউনিয়ন' }}
            </div>
        </div>
        
        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-title">প্রস্তুতকারী</div>
                <div class="signature-line"></div>
                <div class="signature-name">{{ $secretaryName ?? 'মোঃ আব্দুল করিম' }}</div>
                <div class="signature-title">সচিব</div>
                <div class="signature-office">{{ $unionName ?? 'ইউনিয়ন পরিষদ' }}</div>
            </div>
            
            <div class="signature-box">
                <div class="signature-title">প্রদানকারী কর্তৃপক্ষ</div>
                <div class="signature-line"></div>
                <div class="signature-name">{{ $chairmanName ?? 'মোঃ রফিকুল ইসলাম' }}</div>
                <div class="signature-title">চেয়ারম্যান</div>
                <div class="signature-office">{{ $unionName ?? 'ইউনিয়ন পরিষদ' }}</div>
            </div>
        </div>
        
        <!-- Footer Details -->
        <div class="footer-details">
            <div class="details-row">
                <div class="detail-item">
                    <strong>ইস্যুর তারিখ:</strong> {{ $issueDate->format('d-m-Y') }}
                </div>
                <div class="detail-item">
                    <strong>মেয়াদ উত্তীর্ণ:</strong> {{ $validityDate ?? 'N/A' }}
                </div>
                <div class="detail-item">
                    <strong>সনদের ধরণ:</strong> নাগরিকত্ব সনদ
                </div>
            </div>
        </div>
        
        <!-- Security Note -->
        <div class="security-note">
            * জাল সনদ ব্যবহার দণ্ডনীয় অপরাধ * সনদের যথার্থতা যাচাই করতে উপরের QR কোড স্ক্যান করুন
        </div>
        
    </div>
</body>
</html>