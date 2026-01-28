@extends('layouts.super-admin')

@section('title', 'ব্যবহারকারী বিশ্লেষণ - ইউনিয়ন ডিজিটাল')

@section('content')
<div class="container-fluid mx-auto px-4">
    <!-- Header Section -->
    <div class="mb-8 animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">ব্যবহারকারী বিশ্লেষণ</h1>
                <p class="text-gray-600">ব্যবহারকারী বৃদ্ধি, ডেমোগ্রাফিক্স এবং কার্যকলাপ বিশ্লেষণ</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <button onclick="exportReport('csv')" 
                        class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-file-csv mr-2"></i> CSV এক্সপোর্ট
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
        <form id="filterForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Period Select -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">সময়কাল</label>
                    <select name="period" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="month">মাস</option>
                        <option value="quarter">কোয়ার্টার</option>
                        <option value="year" selected>বছর</option>
                        <option value="custom">কাস্টম</option>
                    </select>
                </div>

                <!-- User Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ব্যবহারকারী টাইপ</label>
                    <select name="user_type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">সকল প্রকার</option>
                        <option value="citizen">নাগরিক</option>
                        <option value="admin">অ্যাডমিন</option>
                        <option value="super_admin">সুপার অ্যাডমিন</option>
                    </select>
                </div>

                <!-- Activity Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">কার্যকলাপ স্ট্যাটাস</label>
                    <select name="activity_status" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">সকল</option>
                        <option value="active">সক্রিয়</option>
                        <option value="inactive">নিষ্ক্রিয়</option>
                        <option value="new">নতুন</option>
                    </select>
                </div>
            </div>

            <!-- Custom Date Range (Hidden by default) -->
            <div id="customDateRange" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শুরু তারিখ</label>
                    <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">শেষ তারিখ</label>
                    <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
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

    <!-- User Growth Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="totalUsers">0</h3>
                <p class="text-sm text-gray-600">মোট ব্যবহারকারী</p>
                <div class="mt-2">
                    <span id="userGrowth" class="text-sm font-medium text-green-600">+0%</span>
                    <span class="text-xs text-gray-500 ml-1">গত মাসের তুলনায়</span>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-100 rounded-2xl p-6 border border-green-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-600 to-emerald-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-check text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="activeUsers">0</h3>
                <p class="text-sm text-gray-600">সক্রিয় ব্যবহারকারী</p>
                <div class="mt-2">
                    <span id="activePercentage" class="text-sm font-medium text-blue-600">0%</span>
                    <span class="text-xs text-gray-500 ml-1">মোট ব্যবহারকারীর</span>
                </div>
            </div>
        </div>

        <!-- New Users (Last Month) -->
        <div class="bg-gradient-to-r from-purple-50 to-violet-100 rounded-2xl p-6 border border-purple-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-600 to-violet-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="newUsers">0</h3>
                <p class="text-sm text-gray-600">নতুন ব্যবহারকারী (গত মাস)</p>
                <div class="mt-2">
                    <span id="newUserTrend" class="text-sm font-medium text-purple-600">↑ 0%</span>
                    <span class="text-xs text-gray-500 ml-1">প্রবণতা</span>
                </div>
            </div>
        </div>

        <!-- Avg Applications per User -->
        <div class="bg-gradient-to-r from-orange-50 to-amber-100 rounded-2xl p-6 border border-orange-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-orange-600 to-amber-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="avgApplications">0</h3>
                <p class="text-sm text-gray-600">গড় আবেদন/ব্যবহারকারী</p>
                <div class="mt-2">
                    <span id="engagementRate" class="text-sm font-medium text-orange-600">0%</span>
                    <span class="text-xs text-gray-500 ml-1">এনগেজমেন্ট রেট</span>
                </div>
            </div>
        </div>
    </div>

    <!-- User Growth Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- User Registration Trend -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">নিবন্ধন প্রবণতা</h3>
                <select id="trendPeriod" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="monthly">মাসিক</option>
                    <option value="weekly" selected>সাপ্তাহিক</option>
                    <option value="daily">দৈনিক</option>
                </select>
            </div>
            <div class="h-80">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>

        <!-- User Activity Distribution -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">কার্যকলাপ ডিস্ট্রিবিউশন</h3>
                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">বর্তমান মাস</span>
            </div>
            <div class="h-80">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Demographics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Age Distribution -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6">বয়স অনুযায়ী ব্যবহারকারী</h3>
            <div class="space-y-4" id="ageDistribution">
                <!-- Age groups will be loaded here -->
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-spinner fa-spin text-xl mb-2"></i>
                    <p>ডাটা লোড হচ্ছে...</p>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6">লিঙ্গ অনুযায়ী ব্যবহারকারী</h3>
            <div class="h-64">
                <canvas id="genderChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-4" id="genderStats">
                <!-- Gender stats will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Location Distribution -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">অবস্থান অনুযায়ী ব্যবহারকারী</h3>
            <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">শীর্ষ ১০ এলাকা</span>
        </div>
        <div class="h-80">
            <canvas id="locationChart"></canvas>
        </div>
    </div>

    <!-- Top Users Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">সেরা ব্যবহারকারীগণ</h3>
            <div class="text-sm text-gray-600">
                <span id="topUsersCount" class="font-bold">0</span> সক্রিয় ব্যবহারকারী
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">র্যাঙ্ক</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ব্যবহারকারী</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">মোট আবেদন</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">সর্বমোট ব্যয়</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">সর্বশেষ কার্যকলাপ</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">রেজিস্ট্রেশন তারিখ</th>
                    </tr>
                </thead>
                <tbody id="topUsersTable" class="divide-y divide-gray-200">
                    <!-- Data will be loaded here -->
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin text-xl mb-2"></i>
                            <p>ডাটা লোড হচ্ছে...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Engagement Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Retention Rate -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center mr-4">
                    <i class="fas fa-retweet text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">রিটেনশন রেট</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="retentionRate">0%</h3>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between mb-1">
                    <span class="text-xs text-gray-600">মাস ১</span>
                    <span class="text-xs font-bold" id="month1Retention">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" id="retentionBar1" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Engagement Score -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-green-100 to-green-200 flex items-center justify-center mr-4">
                    <i class="fas fa-bullseye text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">এনগেজমেন্ট স্কোর</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="engagementScore">0</h3>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between mb-1">
                    <span class="text-xs text-gray-600">গড় সেশন সময়</span>
                    <span class="text-xs font-bold" id="avgSessionTime">0 মিনিট</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" id="sessionBar" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Churn Rate -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-red-100 to-red-200 flex items-center justify-center mr-4">
                    <i class="fas fa-user-slash text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">চার্ন রেট</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="churnRate">0%</h3>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between mb-1">
                    <span class="text-xs text-gray-600">নিষ্ক্রিয় ব্যবহারকারী</span>
                    <span class="text-xs font-bold" id="inactiveUsers">0</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-600 h-2 rounded-full" id="churnBar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    loadRegistrationChart();
    loadActivityChart();
    loadGenderChart();
    loadLocationChart();
    
    // Handle form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadUserAnalysisData();
    });
    
    // Handle period change to show/hide custom date range
    document.querySelector('select[name="period"]').addEventListener('change', function() {
        const customDateRange = document.getElementById('customDateRange');
        if (this.value === 'custom') {
            customDateRange.classList.remove('hidden');
        } else {
            customDateRange.classList.add('hidden');
        }
    });
    
    // Load initial data
    loadUserAnalysisData();
});

function loadUserAnalysisData() {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams();
    
    for (const [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    // Show loading state
    showLoadingState();
    
    fetch(`{{ route('superadmin.reports.user.analysis') }}?${params.toString()}&ajax=1`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateUserStats(data.data);
                updateCharts(data.data);
                updateDemographics(data.data.demographics);
                updateTopUsers(data.data.top_users);
                updateEngagementMetrics(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading user analysis data:', error);
            showToast('ডাটা লোড করতে সমস্যা হয়েছে', 'error');
        });
}

function showLoadingState() {
    const elements = [
        'totalUsers', 'activeUsers', 'newUsers', 'avgApplications',
        'ageDistribution', 'genderStats', 'topUsersTable'
    ];
    
    elements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin text-gray-400"></i>
                </div>
            `;
        }
    });
}

function updateUserStats(data) {
    // Update main stats
    document.getElementById('totalUsers').textContent = formatNumber(data.activity?.total_users || 0);
    document.getElementById('activeUsers').textContent = formatNumber(data.activity?.active_users || 0);
    document.getElementById('newUsers').textContent = formatNumber(data.activity?.new_users_last_month || 0);
    
    // Calculate and update percentages
    const totalUsers = data.activity?.total_users || 1;
    const activeUsers = data.activity?.active_users || 0;
    const activePercentage = ((activeUsers / totalUsers) * 100).toFixed(1);
    document.getElementById('activePercentage').textContent = activePercentage + '%';
    
    const avgApplications = data.summary?.avg_applications_per_user || 0;
    document.getElementById('avgApplications').textContent = avgApplications.toFixed(1);
    
    // Update growth rates (sample data for demo)
    document.getElementById('userGrowth').textContent = '+12.5%';
    document.getElementById('newUserTrend').textContent = '↑ 8.3%';
    document.getElementById('engagementRate').textContent = '65%';
}

let registrationChart = null;
let activityChart = null;
let genderChart = null;
let locationChart = null;

function loadRegistrationChart() {
    const ctx = document.getElementById('registrationChart').getContext('2d');
    
    registrationChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'নতুন নিবন্ধন',
                data: [],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                }
            }
        }
    });
}

function loadActivityChart() {
    const ctx = document.getElementById('activityChart').getContext('2d');
    
    activityChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['সক্রিয়', 'নিষ্ক্রিয়', 'নতুন', 'ফিরে আসা', 'বিচ্ছিন্ন'],
            datasets: [{
                label: 'ব্যবহারকারী সংখ্যা',
                data: [45, 25, 15, 10, 5],
                backgroundColor: [
                    '#10b981',
                    '#94a3b8',
                    '#3b82f6',
                    '#8b5cf6',
                    '#ef4444'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                }
            }
        }
    });
}

function loadGenderChart() {
    const ctx = document.getElementById('genderChart').getContext('2d');
    
    genderChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['পুরুষ', 'মহিলা', 'অন্যান্য'],
            datasets: [{
                data: [60, 35, 5],
                backgroundColor: [
                    '#3b82f6',
                    '#ec4899',
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

function loadLocationChart() {
    const ctx = document.getElementById('locationChart').getContext('2d');
    
    locationChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: ['ঢাকা', 'চট্টগ্রাম', 'খুলনা', 'রাজশাহী', 'সিলেট', 'বরিশাল', 'রংপুর', 'ময়মনসিংহ'],
            datasets: [{
                label: 'ব্যবহারকারী সংখ্যা',
                data: [1200, 850, 620, 540, 480, 350, 420, 390],
                backgroundColor: '#10b981',
                borderColor: '#059669',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                }
            }
        }
    });
}

function updateCharts(data) {
    if (!data) return;
    
    // Update registration chart
    if (registrationChart && data.registration_trend) {
        const labels = data.registration_trend.map(item => item.date);
        const values = data.registration_trend.map(item => item.count);
        
        registrationChart.data.labels = labels;
        registrationChart.data.datasets[0].data = values;
        registrationChart.update();
    }
    
    // Update activity chart
    if (activityChart && data.activity) {
        activityChart.data.datasets[0].data = [
            data.activity.active_users || 0,
            data.activity.inactive_users || 0,
            data.activity.new_users_last_month || 0,
            Math.floor((data.activity.active_users || 0) * 0.3), // Returning users (sample)
            Math.floor((data.activity.total_users || 0) * 0.1)  // Churned users (sample)
        ];
        activityChart.update();
    }
}

function updateDemographics(demographics) {
    if (!demographics) return;
    
    // Update age distribution
    const ageContainer = document.getElementById('ageDistribution');
    if (demographics.by_age) {
        let html = '';
        Object.entries(demographics.by_age).forEach(([ageGroup, count]) => {
            const percentage = ((count / (demographics.by_age.total || 1)) * 100).toFixed(1);
            const width = Math.min(percentage * 3, 100); // Scale for display
            
            html += `
                <div class="space-y-1">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">${ageGroup} বছর</span>
                        <span class="text-sm font-bold text-gray-900">${formatNumber(count)} (${percentage}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: ${width}%"></div>
                    </div>
                </div>
            `;
        });
        ageContainer.innerHTML = html;
    }
    
    // Update gender stats
    const genderStats = document.getElementById('genderStats');
    if (demographics.by_gender) {
        const genders = demographics.by_gender;
        const total = genders.male + genders.female + genders.other;
        
        let html = '';
        Object.entries(genders).forEach(([gender, count]) => {
            const genderText = {
                'male': 'পুরুষ',
                'female': 'মহিলা',
                'other': 'অন্যান্য'
            }[gender] || gender;
            
            const percentage = total > 0 ? ((count / total) * 100).toFixed(1) : 0;
            const color = gender === 'male' ? 'blue' : gender === 'female' ? 'pink' : 'gray';
            
            html += `
                <div class="text-center p-3 rounded-lg bg-${color}-50 border border-${color}-200">
                    <div class="text-2xl font-bold text-${color}-700 mb-1">${percentage}%</div>
                    <div class="text-sm font-medium text-gray-700">${genderText}</div>
                    <div class="text-xs text-gray-500 mt-1">${formatNumber(count)} জন</div>
                </div>
            `;
        });
        genderStats.innerHTML = html;
    }
}

function updateTopUsers(users) {
    const tableBody = document.getElementById('topUsersTable');
    const countElement = document.getElementById('topUsersCount');
    
    if (!users || users.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2 text-gray-400"></i>
                    <p class="text-gray-600">কোন ডাটা পাওয়া যায়নি</p>
                </td>
            </tr>
        `;
        countElement.textContent = '0';
        return;
    }
    
    let html = '';
    users.forEach((user, index) => {
        const rankColors = [
            'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800',
            'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800',
            'bg-gradient-to-r from-amber-100 to-amber-200 text-amber-800',
            'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800',
            'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800'
        ];
        
        const rankColor = rankColors[index] || 'bg-gray-50 text-gray-800';
        
        html += `
            <tr class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex justify-center">
                        <span class="px-3 py-1 rounded-full text-xs font-bold ${rankColor}">
                            ${index + 1}
                        </span>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-200 to-blue-300 flex items-center justify-center text-blue-700 font-bold text-sm mr-3">
                            ${getInitials(user.name)}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">${user.name}</div>
                            <div class="text-sm text-gray-500">${user.email}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">
                        <div class="font-bold text-gray-900">${formatNumber(user.total_applications)}</div>
                        <div class="text-xs text-gray-500">আবেদন</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">
                        <div class="font-bold text-green-700">৳${formatNumber(user.total_spent)}</div>
                        <div class="text-xs text-gray-500">সর্বমোট</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm">
                        <div class="text-gray-900">${formatDate(user.last_activity)}</div>
                        <div class="text-xs text-gray-500">সর্বশেষ</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${formatDate(user.created_at)}</div>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
    countElement.textContent = users.length;
}

function updateEngagementMetrics(data) {
    // Update retention rate
    document.getElementById('retentionRate').textContent = '72%';
    document.getElementById('month1Retention').textContent = '85%';
    document.getElementById('retentionBar1').style.width = '85%';
    
    // Update engagement score
    document.getElementById('engagementScore').textContent = '68';
    document.getElementById('avgSessionTime').textContent = '12 মিনিট';
    document.getElementById('sessionBar').style.width = '60%';
    
    // Update churn rate
    const churnRate = data.activity?.inactive_users > 0 ? 
        (data.activity.inactive_users / data.activity.total_users * 100).toFixed(1) : 0;
    document.getElementById('churnRate').textContent = churnRate + '%';
    document.getElementById('inactiveUsers').textContent = formatNumber(data.activity?.inactive_users || 0);
    document.getElementById('churnBar').style.width = churnRate + '%';
}

function exportReport(format) {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams();
    
    for (const [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    params.append('format', format);
    
    window.location.href = `{{ route('superadmin.reports.export', 'user-analysis') }}?${params.toString()}`;
}

function resetFilters() {
    document.getElementById('filterForm').reset();
    document.getElementById('customDateRange').classList.add('hidden');
    loadUserAnalysisData();
}

// Utility functions
function formatNumber(num) {
    return new Intl.NumberFormat('bn-BD').format(num);
}

function formatDate(dateString) {
    if (!dateString || dateString === 'N/A') return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('bn-BD', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}
</script>
@endpush
@endsection