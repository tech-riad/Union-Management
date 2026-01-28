@extends('layouts.super-admin')

@section('title', 'ব্যাকআপ সেটিংস')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-cogs mr-2 text-blue-600"></i>
                        অটো ব্যাকআপ সেটিংস
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">
                        স্বয়ংক্রিয় ব্যাকআপ সিস্টেম কনফিগার করুন
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 text-xs font-bold rounded-full 
                        {{ $auto_backup_enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $auto_backup_enabled ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Settings Form -->
        <form action="{{ route('super_admin.settings.update-backup') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Auto Backup Enable -->
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                    <div>
                        <h4 class="font-bold text-gray-800">স্বয়ংক্রিয় ব্যাকআপ</h4>
                        <p class="text-sm text-gray-600 mt-1">সিস্টেম স্বয়ংক্রিয়ভাবে ব্যাকআপ তৈরি করবে</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_backup_enabled" value="1" 
                               class="sr-only peer" {{ $auto_backup_enabled ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                    peer-checked:after:translate-x-full peer-checked:after:border-white 
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                    after:bg-white after:border-gray-300 after:border after:rounded-full 
                                    after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                        </div>
                    </label>
                </div>
                
                <!-- Schedule Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Schedule Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                            ব্যাকআপ সময়সূচী
                        </label>
                        <select name="schedule" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="daily" {{ $schedule == 'daily' ? 'selected' : '' }}>প্রতিদিন</option>
                            <option value="weekly" {{ $schedule == 'weekly' ? 'selected' : '' }}>প্রতি সপ্তাহে</option>
                            <option value="monthly" {{ $schedule == 'monthly' ? 'selected' : '' }}>প্রতি মাসে</option>
                        </select>
                    </div>
                    
                    <!-- Backup Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-1 text-blue-500"></i>
                            ব্যাকআপ সময়
                        </label>
                        <input type="time" name="backup_time" 
                               value="{{ $backup_time }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <!-- Backup Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-database mr-1 text-blue-500"></i>
                        ব্যাকআপ টাইপ
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 
                                    {{ $backup_type == 'database' ? 'border-blue-500 bg-blue-50' : '' }}">
                            <input type="radio" name="backup_type" value="database" 
                                   class="mr-3 text-blue-600" {{ $backup_type == 'database' ? 'checked' : '' }}>
                            <div>
                                <p class="font-medium text-gray-800">ডাটাবেস</p>
                                <p class="text-sm text-gray-600">শুধু ডাটাবেস ব্যাকআপ</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 
                                    {{ $backup_type == 'files' ? 'border-blue-500 bg-blue-50' : '' }}">
                            <input type="radio" name="backup_type" value="files" 
                                   class="mr-3 text-blue-600" {{ $backup_type == 'files' ? 'checked' : '' }}>
                            <div>
                                <p class="font-medium text-gray-800">ফাইলস</p>
                                <p class="text-sm text-gray-600">শুধু গুরুত্বপূর্ণ ফাইল</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 
                                    {{ $backup_type == 'all' ? 'border-blue-500 bg-blue-50' : '' }}">
                            <input type="radio" name="backup_type" value="all" 
                                   class="mr-3 text-blue-600" {{ $backup_type == 'all' ? 'checked' : '' }}>
                            <div>
                                <p class="font-medium text-gray-800">সম্পূর্ণ ব্যাকআপ</p>
                                <p class="text-sm text-gray-600">ডাটাবেস + ফাইলস</p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Retention Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Retention Days -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-history mr-1 text-blue-500"></i>
                            ব্যাকআপ রাখার সময় (দিন)
                        </label>
                        <input type="number" name="retention_days" 
                               value="{{ $retention_days }}" min="1" max="365"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">পুরানো ব্যাকআপগুলি স্বয়ংক্রিয়ভাবে ডিলিট হবে</p>
                    </div>
                    
                    <!-- Max Backups -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-save mr-1 text-blue-500"></i>
                            সর্বোচ্চ ব্যাকআপ সংখ্যা
                        </label>
                        <input type="number" name="max_backups" 
                               value="{{ $max_backups }}" min="1" max="100"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">সর্বোচ্চ কতগুলি ব্যাকআপ রাখা হবে</p>
                    </div>
                </div>
                
                <!-- Notification Settings -->
                <div class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg">
                    <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-bell mr-2 text-blue-600"></i>
                        নোটিফিকেশন সেটিংস
                    </h4>
                    
                    <div class="space-y-4">
                        <!-- Enable Notifications -->
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-800">নোটিফিকেশন সক্রিয় করুন</p>
                                <p class="text-sm text-gray-600">ব্যাকআপ সফল/ব্যর্থ হলে ইমেইল পাঠানো হবে</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notifications_enabled" value="1" 
                                       class="sr-only peer" {{ $notifications_enabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                            peer-checked:after:translate-x-full peer-checked:after:border-white 
                                            after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                            after:bg-white after:border-gray-300 after:border after:rounded-full 
                                            after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500">
                                </div>
                            </label>
                        </div>
                        
                        <!-- Notification Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-1 text-blue-500"></i>
                                নোটিফিকেশন ইমেইল
                            </label>
                            <input type="email" name="notification_email" 
                                   value="{{ $notification_email }}"
                                   placeholder="admin@example.com"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
                
                <!-- Backup Stats -->
                <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-lg">
                    <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                        ব্যাকআপ পরিসংখ্যান
                    </h4>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                            <p class="text-2xl font-bold text-blue-600">{{ $backup_count ?? 0 }}</p>
                            <p class="text-sm text-gray-600 mt-1">মোট ব্যাকআপ</p>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                            <p class="text-2xl font-bold text-green-600">{{ $last_backup_size ?? '0 MB' }}</p>
                            <p class="text-sm text-gray-600 mt-1">সর্বশেষ সাইজ</p>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                            <p class="text-2xl font-bold text-purple-600">{{ $available_space ?? '0 GB' }}</p>
                            <p class="text-sm text-gray-600 mt-1">মুক্ত স্টোরেজ</p>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                            <p class="text-2xl font-bold text-amber-600">{{ $next_backup ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600 mt-1">পরবর্তী ব্যাকআপ</p>
                        </div>
                    </div>
                    
                    <!-- Additional Stats -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 bg-white rounded-lg shadow-sm">
                            <div class="flex justify-between items-center">
                                <p class="text-gray-700">মোট ব্যাকআপ সাইজ:</p>
                                <p class="font-bold text-gray-900">{{ $total_backup_size ?? '0 MB' }}</p>
                            </div>
                        </div>
                        <div class="p-3 bg-white rounded-lg shadow-sm">
                            <div class="flex justify-between items-center">
                                <p class="text-gray-700">সর্বশেষ ব্যাকআপ:</p>
                                <p class="font-medium text-gray-900">{{ $last_backup_time ?? 'কোনো ব্যাকআপ নেই' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end space-x-3">
                <button type="button" onclick="testBackup()" 
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition shadow-md hover:shadow-lg">
                    <i class="fas fa-play-circle mr-2"></i>
                    টেস্ট ব্যাকআপ
                </button>
                
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    সেটিংস সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
    
    <!-- Backup Management Section -->
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-tools mr-2 text-blue-600"></i>
                ব্যাকআপ ব্যবস্থাপনা
            </h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button onclick="getBackupLogs()" 
                        class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg hover:bg-blue-100 transition flex items-center justify-center">
                    <i class="fas fa-list-alt text-blue-600 mr-2"></i>
                    <span>ব্যাকআপ লগ দেখুন</span>
                </button>
                
                <button onclick="runBackupCleanup()" 
                        class="p-4 bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 rounded-lg hover:bg-amber-100 transition flex items-center justify-center">
                    <i class="fas fa-broom text-amber-600 mr-2"></i>
                    <span>পুরানো ব্যাকআপ ক্লিনআপ</span>
                </button>
                
                <button onclick="checkSystemHealth()" 
                        class="p-4 bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg hover:bg-green-100 transition flex items-center justify-center">
                    <i class="fas fa-heartbeat text-green-600 mr-2"></i>
                    <span>সিস্টেম হেলথ চেক</span>
                </button>
            </div>
            
            <!-- Backup Logs Container -->
            <div id="backupLogsContainer" class="mt-6 hidden">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-bold text-gray-800">ব্যাকআপ লগ</h4>
                    <button onclick="closeBackupLogs()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="backupLogsContent" class="bg-gray-50 p-4 rounded-lg">
                    <!-- Logs will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Backup Modal -->
<div id="testBackupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">টেস্ট ব্যাকআপ</h3>
                <button onclick="closeTestModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 text-center">
                <i class="fas fa-play-circle text-4xl text-blue-500 mb-4"></i>
                <h4 class="font-bold text-lg mb-2">টেস্ট ব্যাকআপ শুরু করবেন?</h4>
                <p class="text-gray-600 mb-6">এটি একটি টেস্ট ব্যাকআপ তৈরি করবে যাতে সিস্টেমের কার্যকারিতা যাচাই করা যাবে</p>
                
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="startTestBackup('database')" 
                            class="p-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition">
                        <i class="fas fa-database mr-2"></i>
                        ডাটাবেস
                    </button>
                    <button onclick="startTestBackup('full')" 
                            class="p-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition">
                        <i class="fas fa-copy mr-2"></i>
                        সম্পূর্ণ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Health Modal -->
<div id="systemHealthModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">সিস্টেম হেলথ চেক</h3>
                <button onclick="closeSystemHealthModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="systemHealthContent">
                    <!-- Health data will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ডিবাগিংয়ের জন্য
console.log('Backup settings page loaded');
console.log('Test backup route:', "{{ route('super_admin.settings.test-backup') }}");
console.log('CSRF Token:', "{{ csrf_token() }}");

function testBackup() {
    console.log('Opening test backup modal');
    document.getElementById('testBackupModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeTestModal() {
    document.getElementById('testBackupModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function startTestBackup(type) {
    console.log('Starting test backup, type:', type);
    closeTestModal();
    
    // Show loading
    showNotification('ব্যাকআপ শুরু হচ্ছে...', 'info');
    
    fetch("{{ route('super_admin.settings.test-backup') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            type: type,
            _token: "{{ csrf_token() }}"
        }),
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            // Try to get error details
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error(`HTTP error! status: ${response.status}, text: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showNotification('✅ টেস্ট ব্যাকআপ সফল: ' + data.message, 'success');
            // পেজ রিফ্রেশ করে নতুন ডেটা লোড করুন
            setTimeout(() => {
                location.reload();
            }, 3000);
        } else {
            showNotification('❌ ব্যাকআপ ব্যর্থ: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showNotification('❌ নেটওয়ার্ক ত্রুটি: ' + error.message, 'error');
        
        // Alternative: Try with form submission
        console.log('Trying alternative method...');
        tryAlternativeMethod(type);
    });
}

// Alternative method using form submission
function tryAlternativeMethod(type) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('super_admin.settings.test-backup') }}";
    form.style.display = 'none';
    
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = "{{ csrf_token() }}";
    
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'type';
    typeInput.value = type;
    
    form.appendChild(tokenInput);
    form.appendChild(typeInput);
    document.body.appendChild(form);
    
    form.submit();
}

function getBackupLogs() {
    console.log('Getting backup logs...');
    showNotification('লগ লোড হচ্ছে...', 'info');
    
    fetch("{{ route('super_admin.settings.backup.logs') }}", {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Logs data:', data);
        if (data.success) {
            const container = document.getElementById('backupLogsContainer');
            const content = document.getElementById('backupLogsContent');
            
            if (data.logs && data.logs.length > 0) {
                let html = '<div class="space-y-3">';
                data.logs.forEach(log => {
                    html += `
                        <div class="p-3 bg-white border border-gray-200 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-800">${log.filename}</span>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    ${log.type === 'database' ? 'bg-blue-100 text-blue-800' : 
                                      log.type === 'files' ? 'bg-green-100 text-green-800' : 
                                      'bg-purple-100 text-purple-800'}">
                                    ${log.type}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <div>
                                    <span class="text-sm text-gray-600">সাইজ:</span>
                                    <span class="text-sm font-medium ml-1">${log.size}</span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">সময়:</span>
                                    <span class="text-sm font-medium ml-1">${log.time_ago}</span>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">${log.modified}</div>
                        </div>
                    `;
                });
                html += '</div>';
                
                if (data.total > 10) {
                    html += `<div class="mt-3 text-center text-sm text-gray-600">
                        মোট ${data.total}টি ব্যাকআপ, শেষ ১০টি দেখানো হচ্ছে
                    </div>`;
                }
                
                content.innerHTML = html;
                showNotification('লগ সফলভাবে লোড হয়েছে', 'success');
            } else {
                content.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">কোনো ব্যাকআপ লগ পাওয়া যায়নি</p>
                    </div>
                `;
                showNotification('কোনো লগ পাওয়া যায়নি', 'info');
            }
            
            container.classList.remove('hidden');
            container.scrollIntoView({ behavior: 'smooth' });
        } else {
            showNotification('❌ লগ লোড করতে সমস্যা: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error loading logs:', error);
        showNotification('❌ লগ লোড করতে ব্যর্থ: ' + error.message, 'error');
    });
}

function closeBackupLogs() {
    document.getElementById('backupLogsContainer').classList.add('hidden');
}

function runBackupCleanup() {
    if (!confirm('আপনি কি পুরানো ব্যাকআপগুলি ডিলিট করতে চান?\n\nএই কাজটি পূর্বাবস্থায় ফিরিয়ে আনা যাবে না।')) {
        return;
    }
    
    const days = prompt('কত দিনের পুরানো ব্যাকআপ ডিলিট করবেন?', '30');
    if (!days || isNaN(days) || days < 1) {
        alert('দয়া করে বৈধ দিন সংখ্যা দিন (১ বা তার বেশি)');
        return;
    }
    
    console.log('Running backup cleanup for days:', days);
    showNotification('ক্লিনআপ চলছে...', 'info');
    
    fetch("{{ route('super_admin.settings.backup.cleanup') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            days: days,
            _token: "{{ csrf_token() }}"
        }),
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Cleanup response:', data);
        if (data.success) {
            showNotification('✅ ব্যাকআপ ক্লিনআপ সফল: ' + data.message, 'success');
            // পেজ রিফ্রেশ করুন
            setTimeout(() => location.reload(), 2000);
        } else {
            showNotification('❌ ক্লিনআপ ব্যর্থ: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Cleanup error:', error);
        showNotification('❌ নেটওয়ার্ক ত্রুটি: ' + error.message, 'error');
    });
}

function checkSystemHealth() {
    console.log('Checking system health...');
    showNotification('হেলথ চেক চলছে...', 'info');
    
    fetch("{{ route('super_admin.settings.backup.health') }}", {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Health data:', data);
        if (data.success) {
            const modal = document.getElementById('systemHealthModal');
            const content = document.getElementById('systemHealthContent');
            
            let html = '<div class="space-y-4">';
            
            if (data.health) {
                Object.keys(data.health).forEach(key => {
                    const item = data.health[key];
                    html += `
                        <div class="p-4 ${item.status ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'} rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <h5 class="font-bold text-gray-800 capitalize">${key.replace('_', ' ')}</h5>
                                <span class="px-2 py-1 text-xs rounded-full ${item.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${item.status ? 'সঠিক' : 'সমস্যা'}
                                </span>
                            </div>
                            <p class="text-sm ${item.status ? 'text-green-700' : 'text-red-700'}">${item.message}</p>
                        </div>
                    `;
                });
            }
            
            html += `
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <h5 class="font-bold text-gray-800">সর্বশেষ চেক</h5>
                        <span class="text-sm text-gray-600">${data.timestamp}</span>
                    </div>
                    <div class="mt-3 text-center">
                        <span class="px-4 py-2 rounded-full ${data.all_healthy ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} font-medium">
                            ${data.all_healthy ? '✅ সবকিছু সঠিকভাবে চলছে' : '⚠️ কিছু সমস্যা রয়েছে'}
                        </span>
                    </div>
                </div>
            `;
            
            html += '</div>';
            
            content.innerHTML = html;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            showNotification('হেলথ চেক সম্পন্ন', 'success');
        } else {
            showNotification('❌ হেলথ চেক ব্যর্থ: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Health check error:', error);
        showNotification('❌ হেলথ চেক করতে ব্যর্থ: ' + error.message, 'error');
    });
}

function closeSystemHealthModal() {
    document.getElementById('systemHealthModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Notification system
function showNotification(message, type = 'info') {
    // Remove existing notifications
    document.querySelectorAll('.notification').forEach(el => el.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in 
        ${type === 'success' ? 'bg-green-500 text-white' : 
          type === 'error' ? 'bg-red-500 text-white' : 
          type === 'info' ? 'bg-blue-500 text-white' : 
          'bg-gray-500 text-white'}`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                          type === 'error' ? 'fa-exclamation-circle' : 
                          type === 'info' ? 'fa-info-circle' : 'fa-bell'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Confirm before form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('আপনি কি সেটিংস সংরক্ষণ করতে চান?')) {
                e.preventDefault();
            }
        });
    }
    
    // Test routes
    console.log('Available routes for debugging:');
    console.log('- Test backup:', "{{ route('super_admin.settings.test-backup') }}");
    console.log('- Backup logs:', "{{ route('super_admin.settings.backup.logs') }}");
    console.log('- Backup cleanup:', "{{ route('super_admin.settings.backup.cleanup') }}");
    console.log('- System health:', "{{ route('super_admin.settings.backup.health') }}");
});
</script>

<style>
.animate-slide-in {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.notification {
    min-width: 300px;
    max-width: 400px;
}
</style>
@endsection