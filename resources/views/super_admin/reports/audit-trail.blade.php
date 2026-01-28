@extends('layouts.super-admin')

@section('title', 'অডিট ট্রেইল - ইউনিয়ন ডিজিটাল')

@section('content')
<div class="container-fluid mx-auto px-4">
    <!-- Header Section -->
    <div class="mb-8 animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">অডিট ট্রেইল</h1>
                <p class="text-gray-600">সিস্টেমের সকল কার্যকলাপ লগ এবং নিরাপত্তা অডিট</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <button onclick="clearOldLogs()" 
                        class="px-4 py-2 bg-gradient-to-r from-red-600 to-rose-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-trash-alt mr-2"></i> পুরনো লগ পরিষ্কার করুন
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
        <form id="filterForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Action Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">কার্যকলাপ টাইপ</label>
                    <select name="action_type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">সকল প্রকার</option>
                        <option value="create">তৈরি</option>
                        <option value="update">আপডেট</option>
                        <option value="delete">মুছে ফেলা</option>
                        <option value="login">লগইন</option>
                        <option value="logout">লগআউট</option>
                        <option value="export">এক্সপোর্ট</option>
                        <option value="import">ইম্পোর্ট</option>
                        <option value="approve">অনুমোদন</option>
                        <option value="reject">প্রত্যাখ্যান</option>
                    </select>
                </div>

                <!-- User Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ব্যবহারকারী টাইপ</label>
                    <select name="user_type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">সকল প্রকার</option>
                        <option value="admin">অ্যাডমিন</option>
                        <option value="super_admin">সুপার অ্যাডমিন</option>
                        <option value="citizen">নাগরিক</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শুরু তারিখ</label>
                    <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শেষ তারিখ</label>
                    <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Search Box -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">অনুসন্ধান</label>
                <div class="relative">
                    <input type="text" name="search" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="ব্যবহারকারী, আইপি, বা কার্যকলাপ অনুসন্ধান করুন...">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="resetFilters()" 
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-redo mr-2"></i> রিসেট
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-filter mr-2"></i> ফিল্টার প্রয়োগ
                </button>
            </div>
        </form>
    </div>

    <!-- Activity Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Activities -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-history text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="totalActivities">0</h3>
                <p class="text-sm text-gray-600">মোট কার্যকলাপ</p>
                <div class="mt-2">
                    <span class="text-xs text-gray-500" id="activityPeriod">গত ৭ দিন</span>
                </div>
            </div>
        </div>

        <!-- Admin Activities -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-100 rounded-2xl p-6 border border-green-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-600 to-emerald-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-tie text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="adminActivities">0</h3>
                <p class="text-sm text-gray-600">অ্যাডমিন কার্যকলাপ</p>
                <div class="mt-2">
                    <span id="adminPercentage" class="text-sm font-medium text-green-600">0%</span>
                </div>
            </div>
        </div>

        <!-- Failed Attempts -->
        <div class="bg-gradient-to-r from-red-50 to-rose-100 rounded-2xl p-6 border border-red-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-red-600 to-rose-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="failedAttempts">0</h3>
                <p class="text-sm text-gray-600">ব্যর্থ চেষ্টা</p>
                <div class="mt-2">
                    <span id="failureRate" class="text-sm font-medium text-red-600">0%</span>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-gradient-to-r from-purple-50 to-violet-100 rounded-2xl p-6 border border-purple-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-600 to-violet-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bell text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="recentActivities">0</h3>
                <p class="text-sm text-gray-600">আজকের কার্যকলাপ</p>
                <div class="mt-2">
                    <span id="activityTrend" class="text-sm font-medium text-purple-600">↑ 0%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Activity Type Distribution -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">কার্যকলাপ টাইপ ডিস্ট্রিবিউশন</h3>
                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">গত ৩০ দিন</span>
            </div>
            <div class="h-80">
                <canvas id="activityTypeChart"></canvas>
            </div>
        </div>

        <!-- Hourly Activity Pattern -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">ঘন্টা অনুযায়ী কার্যকলাপ</h3>
                <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">গড় প্রতিদিন</span>
            </div>
            <div class="h-80">
                <canvas id="hourlyActivityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Audit Trail Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">সকল কার্যকলাপ লগ</h3>
            <div class="text-sm text-gray-600">
                মোট <span id="totalRecords" class="font-bold">0</span> রেকর্ড
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">সময়</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ব্যবহারকারী</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">কার্যকলাপ</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বিস্তারিত</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">আইপি ঠিকানা</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ডিভাইস</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody id="auditTable" class="divide-y divide-gray-200">
                    <!-- Data will be loaded here -->
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin text-xl mb-2"></i>
                            <p>ডাটা লোড হচ্ছে...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="mt-6 flex items-center justify-between">
            <!-- Pagination will be loaded here -->
        </div>
    </div>

    <!-- Security Alerts -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mt-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">নিরাপত্তা অ্যালার্টস</h3>
            <span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full" id="alertCount">0 অ্যালার্ট</span>
        </div>
        
        <div id="securityAlerts" class="space-y-4">
            <!-- Security alerts will be loaded here -->
            <div class="text-center py-8 text-gray-500">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-100 to-green-200 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">কোন নিরাপত্তা অ্যালার্ট নেই</h4>
                <p class="text-gray-600">সিস্টেমটি সম্পূর্ণ নিরাপদে রয়েছে</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    loadActivityTypeChart();
    loadHourlyActivityChart();
    
    // Handle form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadAuditTrailData();
    });
    
    // Load initial data
    loadAuditTrailData();
});

function loadAuditTrailData() {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams();
    
    for (const [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    // Show loading state
    const tableBody = document.getElementById('auditTable');
    tableBody.innerHTML = `
        <tr>
            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                <i class="fas fa-spinner fa-spin text-xl mb-2"></i>
                <p>ডাটা লোড হচ্ছে...</p>
            </td>
        </tr>
    `;
    
    fetch(`{{ route('superadmin.reports.audit.trail') }}?${params.toString()}&ajax=1`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateActivityStats(data);
                updateAuditTable(data.audit_logs);
                updateCharts(data);
                updateSecurityAlerts(data);
            }
        })
        .catch(error => {
            console.error('Error loading audit trail data:', error);
            showToast('ডাটা লোড করতে সমস্যা হয়েছে', 'error');
        });
}

function updateActivityStats(data) {
    // Sample data for demo - replace with actual API data
    document.getElementById('totalActivities').textContent = formatNumber(1245);
    document.getElementById('adminActivities').textContent = formatNumber(856);
    document.getElementById('failedAttempts').textContent = formatNumber(23);
    document.getElementById('recentActivities').textContent = formatNumber(45);
    
    document.getElementById('adminPercentage').textContent = '68.7%';
    document.getElementById('failureRate').textContent = '1.8%';
    document.getElementById('activityTrend').textContent = '↑ 12.5%';
    document.getElementById('activityPeriod').textContent = 'গত ৩০ দিন';
}

let activityTypeChart = null;
let hourlyActivityChart = null;

function loadActivityTypeChart() {
    const ctx = document.getElementById('activityTypeChart').getContext('2d');
    
    activityTypeChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['লগইন', 'আপডেট', 'তৈরি', 'অনুমোদন', 'এক্সপোর্ট', 'অন্যান্য'],
            datasets: [{
                data: [35, 25, 15, 10, 8, 7],
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#8b5cf6',
                    '#f59e0b',
                    '#ef4444',
                    '#94a3b8'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            family: "'Noto Sans Bengali', sans-serif"
                        }
                    }
                }
            }
        }
    });
}

function loadHourlyActivityChart() {
    const ctx = document.getElementById('hourlyActivityChart').getContext('2d');
    
    hourlyActivityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'],
            datasets: [{
                label: 'কার্যকলাপ সংখ্যা',
                data: [5, 3, 8, 45, 68, 52, 35, 18],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            family: "'Noto Sans Bengali', sans-serif"
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'কার্যকলাপ সংখ্যা'
                    }
                }
            }
        }
    });
}

function updateCharts(data) {
    // Update charts with actual data if available
    if (activityTypeChart) {
        // Update with real data from API
    }
    
    if (hourlyActivityChart) {
        // Update with real data from API
    }
}

function updateAuditTable(auditLogs) {
    const tableBody = document.getElementById('auditTable');
    
    if (!auditLogs || auditLogs.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2 text-gray-400"></i>
                    <p class="text-gray-600">কোন ডাটা পাওয়া যায়নি</p>
                </td>
            </tr>
        `;
        return;
    }
    
    // Sample data for demo - replace with actual API data
    const sampleLogs = [
        {
            time: '২০২৪-০১-১৫ ১০:৩০:৪৫',
            user: 'সুপার অ্যাডমিন',
            action: 'লগইন',
            details: 'সফল লগইন',
            ip: '103.150.34.56',
            device: 'Windows/Chrome',
            severity: 'info'
        },
        {
            time: '২০২৪-০১-১৫ ০৯:১৫:২০',
            user: 'অ্যাডমিন - করিম উদ্দিন',
            action: 'আপডেট',
            details: 'আবেদন #1234 আপডেট করা হয়েছে',
            ip: '203.76.128.45',
            device: 'Android/Chrome',
            severity: 'warning'
        },
        {
            time: '২০২৪-০১-১৫ ০৮:৪৫:১০',
            user: 'অজানা ব্যবহারকারী',
            action: 'লগইন চেষ্টা',
            details: 'ব্যর্থ লগইন চেষ্টা',
            ip: '45.67.89.123',
            device: 'Unknown',
            severity: 'danger'
        },
        {
            time: '২০২৪-০১-১৫ ০৭:২০:৩০',
            user: 'সুপার অ্যাডমিন',
            action: 'এক্সপোর্ট',
            details: 'রাজস্ব রিপোর্ট এক্সপোর্ট করা হয়েছে',
            ip: '103.150.34.56',
            device: 'Windows/Chrome',
            severity: 'info'
        },
        {
            time: '২০২৪-০১-১৫ ০৬:৫৫:১৫',
            user: 'অ্যাডমিন - রহিম মিয়া',
            action: 'অনুমোদন',
            details: 'আবেদন #1235 অনুমোদিত হয়েছে',
            ip: '198.54.23.67',
            device: 'Windows/Firefox',
            severity: 'success'
        }
    ];
    
    let html = '';
    sampleLogs.forEach(log => {
        const severityColor = getSeverityColor(log.severity);
        const severityIcon = getSeverityIcon(log.severity);
        
        html += `
            <tr class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${log.time}</div>
                    <div class="text-xs text-gray-500">${getTimeAgo(log.time)}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full ${getUserAvatarColor(log.user)} flex items-center justify-center text-white font-bold text-sm mr-2">
                            ${getInitials(log.user)}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">${log.user}</div>
                            <div class="text-xs text-gray-500">${getUserType(log.user)}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-3 py-1 text-xs ${severityColor} rounded-full">
                        <i class="fas fa-${severityIcon} mr-1"></i> ${log.action}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm text-gray-900 max-w-xs truncate">${log.details}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${log.ip}</div>
                    <div class="text-xs text-gray-500">${getLocationFromIP(log.ip)}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${log.device}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <button onclick="viewLogDetails(this)" 
                            class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                        <i class="fas fa-eye mr-1"></i> দেখুন
                    </button>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
}

function updateSecurityAlerts(data) {
    const container = document.getElementById('securityAlerts');
    const alertCount = document.getElementById('alertCount');
    
    // Sample alerts for demo
    const sampleAlerts = [
        {
            type: 'danger',
            title: 'একাধিক ব্যর্থ লগইন চেষ্টা',
            description: 'একটি আইপি থেকে ৫ টির বেশি ব্যর্থ লগইন চেষ্টা',
            time: '১০ মিনিট আগে',
            ip: '45.67.89.123'
        },
        {
            type: 'warning',
            title: 'অপ্রত্যাশিত অ্যাক্সেস সময়',
            description: 'অ্যাডমিন অ্যাকাউন্ট থেকে রাত ৩টায় অ্যাক্সেস',
            time: '২ ঘন্টা আগে',
            user: 'অ্যাডমিন - করিম উদ্দিন'
        }
    ];
    
    if (sampleAlerts.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-100 to-green-200 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">কোন নিরাপত্তা অ্যালার্ট নেই</h4>
                <p class="text-gray-600">সিস্টেমটি সম্পূর্ণ নিরাপদে রয়েছে</p>
            </div>
        `;
        alertCount.textContent = '0 অ্যালার্ট';
        return;
    }
    
    let html = '';
    sampleAlerts.forEach(alert => {
        const alertColors = {
            danger: 'bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-600',
            warning: 'bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-600',
            info: 'bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-600'
        };
        
        const alertIcons = {
            danger: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        
        html += `
            <div class="${alertColors[alert.type]} p-4 rounded-r-lg">
                <div class="flex items-start">
                    <i class="fas fa-${alertIcons[alert.type]} text-xl ${alert.type === 'danger' ? 'text-red-600' : alert.type === 'warning' ? 'text-yellow-600' : 'text-blue-600'} mr-3 mt-1"></i>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold ${alert.type === 'danger' ? 'text-red-800' : alert.type === 'warning' ? 'text-yellow-800' : 'text-blue-800'} mb-1">${alert.title}</h4>
                            <span class="text-xs text-gray-500">${alert.time}</span>
                        </div>
                        <p class="text-sm ${alert.type === 'danger' ? 'text-red-700' : alert.type === 'warning' ? 'text-yellow-700' : 'text-blue-700'} mb-2">${alert.description}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">${alert.ip || alert.user || ''}</span>
                            <div class="flex space-x-2">
                                <button onclick="investigateAlert(this)" 
                                        class="px-3 py-1 text-xs bg-gray-100 text-gray-800 rounded hover:bg-gray-200 transition-colors duration-200">
                                    তদন্ত করুন
                                </button>
                                <button onclick="dismissAlert(this)" 
                                        class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors duration-200">
                                    বাতিল করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    alertCount.textContent = sampleAlerts.length + ' অ্যালার্ট';
}

function clearOldLogs() {
    if (confirm('আপনি কি ৩০ দিনের পুরনো সমস্ত লগ মুছে ফেলতে চান? এটি অপরিবর্তনীয়।')) {
        showToast('পুরনো লগগুলি পরিষ্কার করা হচ্ছে...', 'info');
        // Implement log clearing functionality
    }
}

function resetFilters() {
    document.getElementById('filterForm').reset();
    loadAuditTrailData();
}

function viewLogDetails(button) {
    // Implement log details view modal
    showToast('লগ ডিটেইলস দেখানো হবে', 'info');
}

function investigateAlert(button) {
    // Implement alert investigation
    showToast('তদন্ত শুরু হয়েছে', 'info');
}

function dismissAlert(button) {
    const alertDiv = button.closest('div');
    alertDiv.style.opacity = '0';
    alertDiv.style.transform = 'translateX(20px)';
    alertDiv.style.transition = 'all 0.3s ease';
    
    setTimeout(() => {
        alertDiv.remove();
        updateAlertCount();
    }, 300);
}

function updateAlertCount() {
    const alerts = document.querySelectorAll('#securityAlerts > div');
    const alertCount = document.getElementById('alertCount');
    alertCount.textContent = alerts.length + ' অ্যালার্ট';
}

// Utility functions
function getSeverityColor(severity) {
    switch(severity) {
        case 'danger': return 'bg-red-100 text-red-800';
        case 'warning': return 'bg-yellow-100 text-yellow-800';
        case 'success': return 'bg-green-100 text-green-800';
        default: return 'bg-blue-100 text-blue-800';
    }
}

function getSeverityIcon(severity) {
    switch(severity) {
        case 'danger': return 'exclamation-circle';
        case 'warning': return 'exclamation-triangle';
        case 'success': return 'check-circle';
        default: return 'info-circle';
    }
}

function getUserAvatarColor(user) {
    if (user.includes('সুপার অ্যাডমিন')) return 'bg-gradient-to-r from-blue-600 to-blue-800';
    if (user.includes('অ্যাডমিন')) return 'bg-gradient-to-r from-green-600 to-emerald-800';
    if (user.includes('অজানা')) return 'bg-gradient-to-r from-red-600 to-rose-800';
    return 'bg-gradient-to-r from-gray-600 to-gray-800';
}

function getUserType(user) {
    if (user.includes('সুপার অ্যাডমিন')) return 'সুপার অ্যাডমিন';
    if (user.includes('অ্যাডমিন')) return 'অ্যাডমিন';
    if (user.includes('অজানা')) return 'অজানা';
    return 'ব্যবহারকারী';
}

function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}

function getTimeAgo(dateString) {
    // Simplified time ago calculation
    return '১০ মিনিট আগে';
}

function getLocationFromIP(ip) {
    // Simplified IP location
    return 'ঢাকা, বাংলাদেশ';
}

function formatNumber(num) {
    return new Intl.NumberFormat('bn-BD').format(num);
}
</script>
@endpush
@endsection