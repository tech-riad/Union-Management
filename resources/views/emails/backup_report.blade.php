<!DOCTYPE html>
<html>
<head>
    <title>ব্যাকআপ রিপোর্ট</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; border-top: none; border-radius: 0 0 10px 10px; }
        .stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 20px 0; }
        .stat-box { background: white; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-value { font-size: 24px; font-weight: bold; color: #667eea; }
        .stat-label { font-size: 12px; color: #666; margin-top: 5px; }
        .status { padding: 10px; border-radius: 5px; margin: 5px 0; }
        .status-success { background: #d4edda; color: #155724; }
        .status-warning { background: #fff3cd; color: #856404; }
        .status-danger { background: #f8d7da; color: #721c24; }
        .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>সাপ্তাহিক ব্যাকআপ রিপোর্ট</h1>
            <p>{{ $report_date }}</p>
        </div>
        
        <div class="content">
            <h2>📊 ব্যাকআপ পরিসংখ্যান</h2>
            
            <div class="stats">
                <div class="stat-box">
                    <div class="stat-value">{{ $total_backups }}</div>
                    <div class="stat-label">মোট ব্যাকআপ</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $last_7_days }}</div>
                    <div class="stat-label">গত ৭ দিন</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $last_30_days }}</div>
                    <div class="stat-label">গত ৩০ দিন</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $total_size }}</div>
                    <div class="stat-label">মোট সাইজ</div>
                </div>
            </div>
            
            <h2>🔄 সর্বশেষ ব্যাকআপ</h2>
            @if(is_array($latest_backup) && isset($latest_backup['filename']))
            <div class="status status-success">
                <strong>ফাইল:</strong> {{ $latest_backup['filename'] }}<br>
                <strong>সাইজ:</strong> {{ $latest_backup['size'] }}<br>
                <strong>তারিখ:</strong> {{ $latest_backup['date'] }}
            </div>
            @else
            <div class="status status-warning">
                কোনো ব্যাকআপ পাওয়া যায়নি
            </div>
            @endif
            
            <h2>⚙️ সিস্টেম স্ট্যাটাস</h2>
            <div class="status {{ strpos($system_status['database'], '✅') !== false ? 'status-success' : 'status-danger' }}">
                <strong>ডাটাবেস:</strong> {{ $system_status['database'] }}
            </div>
            <div class="status status-success">
                <strong>স্টোরেজ:</strong> {{ $system_status['storage'] }}
            </div>
            <div class="status {{ $system_status['backup_dir'] ? 'status-success' : 'status-danger' }}">
                <strong>ব্যাকআপ ডিরেক্টরি:</strong> {{ $system_status['backup_dir'] ? '✅ Available' : '❌ Missing' }}
            </div>
            
            <h2>📈 রিকমেন্ডেশন</h2>
            <ul>
                <li>সাপ্তাহিক ব্যাকআপ চালু রাখুন</li>
                <li>রিমোট স্টোরেজে ব্যাকআপ কপি করুন</li>
                <li>মাসে অন্তত একবার ব্যাকআপ টেস্ট করুন</li>
                <li>পুরানো ব্যাকআপ রিভিউ করুন</li>
            </ul>
            
            <div class="footer">
                <p>এই রিপোর্টটি স্বয়ংক্রিয়ভাবে তৈরি হয়েছে</p>
                <p>{{ config('app.name') }} - অটোমেটিক ব্যাকআপ সিস্টেম</p>
            </div>
        </div>
    </div>
</body>
</html>