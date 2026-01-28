@extends('layouts.super-admin')

@section('title', 'আর্থিক বিশ্লেষণ - ইউনিয়ন ডিজিটাল')

@section('content')
<div class="container-fluid mx-auto px-4">
    <!-- Header Section -->
    <div class="mb-8 animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">আর্থিক বিশ্লেষণ</h1>
                <p class="text-gray-600">রাজস্ব প্রবণতা, পেমেন্ট বিশ্লেষণ এবং ভবিষ্যদ্বাণী</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <button onclick="exportReport('csv')" 
                        class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-file-csv mr-2"></i> CSV এক্সপোর্ট
                </button>
                <button onclick="exportReport('pdf')" 
                        class="px-4 py-2 bg-gradient-to-r from-red-600 to-rose-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> PDF এক্সপোর্ট
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

                <!-- Certificate Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">সার্টিফিকেট টাইপ</label>
                    <select name="certificate_type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">সকল সার্টিফিকেট</option>
                        <option value="birth">জন্ম নিবন্ধন</option>
                        <option value="death">মৃত্যু সার্টিফিকেট</option>
                        <option value="income">আয় সার্টিফিকেট</option>
                        <option value="character">চরিত্র সার্টিফিকেট</option>
                    </select>
                </div>

                <!-- Payment Method Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পেমেন্ট মেথড</label>
                    <select name="payment_method" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">সকল পদ্ধতি</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                        <option value="bank">ব্যাংক</option>
                        <option value="cash">নগদ</option>
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

    <!-- Financial Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center mr-4">
                    <i class="fas fa-money-bill-wave text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">মোট রাজস্ব</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="totalRevenue">৳ 0</h3>
                </div>
            </div>
            <div class="mt-2">
                <span id="revenueGrowth" class="text-sm font-medium text-green-600">+0%</span>
                <span class="text-xs text-gray-500 ml-1">গত সময়ের তুলনায়</span>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-100 rounded-2xl p-6 border border-green-200 shadow-sm">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-green-600 to-emerald-800 flex items-center justify-center mr-4">
                    <i class="fas fa-exchange-alt text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">মোট লেনদেন</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="totalTransactions">0</h3>
                </div>
            </div>
            <div class="mt-2">
                <span id="transactionGrowth" class="text-sm font-medium text-green-600">+0%</span>
                <span class="text-xs text-gray-500 ml-1">গত সময়ের তুলনায়</span>
            </div>
        </div>

        <!-- Average Transaction Value -->
        <div class="bg-gradient-to-r from-purple-50 to-violet-100 rounded-2xl p-6 border border-purple-200 shadow-sm">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-purple-600 to-violet-800 flex items-center justify-center mr-4">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">গড় লেনদেন মান</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="avgTransaction">৳ 0</h3>
                </div>
            </div>
            <div class="mt-2">
                <span id="avgGrowth" class="text-sm font-medium text-blue-600">+0%</span>
                <span class="text-xs text-gray-500 ml-1">মান বৃদ্ধি</span>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-gradient-to-r from-orange-50 to-amber-100 rounded-2xl p-6 border border-orange-200 shadow-sm">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-orange-600 to-amber-800 flex items-center justify-center mr-4">
                    <i class="fas fa-percentage text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">সাফল্যের হার</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="successRate">0%</h3>
                </div>
            </div>
            <div class="mt-2">
                <span id="rateChange" class="text-sm font-medium text-green-600">+0%</span>
                <span class="text-xs text-gray-500 ml-1">উন্নতি</span>
            </div>
        </div>
    </div>

    <!-- Revenue Trend Chart -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">রাজস্ব প্রবণতা</h3>
            <select id="trendType" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="daily">দৈনিক</option>
                <option value="weekly">সাপ্তাহিক</option>
                <option value="monthly" selected>মাসিক</option>
                <option value="yearly">বার্ষিক</option>
            </select>
        </div>
        <div class="h-80">
            <canvas id="revenueTrendChart"></canvas>
        </div>
    </div>

    <!-- Payment Analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Payment Method Distribution -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">পেমেন্ট মেথড ডিস্ট্রিবিউশন</h3>
                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">মোট রাজস্ব</span>
            </div>
            <div class="h-80">
                <canvas id="paymentMethodChart"></canvas>
            </div>
        </div>

        <!-- Certificate Type Revenue -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">সার্টিফিকেট টাইপ অনুযায়ী রাজস্ব</h3>
                <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">শীর্ষ ১০</span>
            </div>
            <div class="h-80">
                <canvas id="certificateRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Revenue Prediction -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">ভবিষ্যত রাজস্ব ভবিষ্যদ্বাণী</h3>
            <span class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-full">পরবর্তী ৩ মাস</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Predicted Revenue -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1" id="predictedRevenue">৳ 0</h3>
                    <p class="text-sm text-gray-600">ভবিষ্যদ্বাণীকৃত রাজস্ব</p>
                    <div class="mt-2">
                        <span id="predictionConfidence" class="text-sm font-medium text-green-600">0% আস্থা</span>
                    </div>
                </div>
            </div>

            <!-- Growth Rate -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-100 rounded-xl p-6">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-600 to-emerald-800 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-arrow-up text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1" id="growthRate">0%</h3>
                    <p class="text-sm text-gray-600">প্রত্যাশিত বৃদ্ধির হার</p>
                    <div class="mt-2">
                        <span id="growthTrend" class="text-sm font-medium text-blue-600">স্থিতিশীল</span>
                    </div>
                </div>
            </div>

            <!-- Risk Assessment -->
            <div class="bg-gradient-to-r from-orange-50 to-amber-100 rounded-xl p-6">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-orange-600 to-amber-800 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1" id="riskLevel">নিম্ন</h3>
                    <p class="text-sm text-gray-600">ঝুঁকি মাত্রা</p>
                    <div class="mt-2">
                        <span id="riskScore" class="text-sm font-medium text-orange-600">স্কোর: 0/100</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prediction Chart -->
        <div class="h-64">
            <canvas id="predictionChart"></canvas>
        </div>
    </div>

    <!-- Financial KPIs -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">আর্থিক কর্মক্ষমতা সূচক (KPIs)</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Monthly Recurring Revenue -->
            <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">মাসিক পুনরাবৃত্ত রাজস্ব</span>
                    <i class="fas fa-info-circle text-gray-400"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-800 mb-1" id="mrr">৳ 0</h4>
                <div class="flex items-center text-sm">
                    <span id="mrrGrowth" class="text-green-600 font-medium">+0%</span>
                    <span class="text-gray-500 ml-2">গত মাসের তুলনায়</span>
                </div>
            </div>

            <!-- Customer Acquisition Cost -->
            <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">গ্রাহক অর্জন ব্যয়</span>
                    <i class="fas fa-info-circle text-gray-400"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-800 mb-1" id="cac">৳ 0</h4>
                <div class="flex items-center text-sm">
                    <span id="cacTrend" class="text-red-600 font-medium">+0%</span>
                    <span class="text-gray-500 ml-2">বৃদ্ধি</span>
                </div>
            </div>

            <!-- Lifetime Value -->
            <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">গ্রাহক জীবনকাল মূল্য</span>
                    <i class="fas fa-info-circle text-gray-400"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-800 mb-1" id="ltv">৳ 0</h4>
                <div class="flex items-center text-sm">
                    <span id="ltvGrowth" class="text-green-600 font-medium">+0%</span>
                    <span class="text-gray-500 ml-2">উন্নতি</span>
                </div>
            </div>

            <!-- LTV to CAC Ratio -->
            <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">LTV:CAC অনুপাত</span>
                    <i class="fas fa-info-circle text-gray-400"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-800 mb-1" id="ltvCacRatio">0:1</h4>
                <div class="flex items-center text-sm">
                    <span id="ratioStatus" class="text-green-600 font-medium">ভাল</span>
                    <span class="text-gray-500 ml-2">স্বাস্থ্য</span>
                </div>
            </div>
        </div>

        <!-- KPI Progress Bars -->
        <div class="mt-6 space-y-4">
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">রাজস্ব লক্ষ্যমাত্রা</span>
                    <span class="text-sm font-bold" id="revenueTarget">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" id="revenueTargetBar" style="width: 0%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">লেনদেন বৃদ্ধি</span>
                    <span class="text-sm font-bold" id="transactionTarget">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full" id="transactionTargetBar" style="width: 0%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">সাফল্যের হার</span>
                    <span class="text-sm font-bold" id="successTarget">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-purple-600 h-3 rounded-full" id="successTargetBar" style="width: 0%"></div>
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
    loadRevenueTrendChart();
    loadPaymentMethodChart();
    loadCertificateRevenueChart();
    loadPredictionChart();
    
    // Handle form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadFinancialAnalyticsData();
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
    loadFinancialAnalyticsData();
});

function loadFinancialAnalyticsData() {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams();
    
    for (const [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    fetch(`{{ route('superadmin.reports.financial.analytics') }}?${params.toString()}&ajax=1`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateFinancialSummary(data.data.financial_summary);
                updateRevenuePrediction(data.data.prediction);
                updateCharts(data.data);
                updateKPIs(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading financial analytics data:', error);
            showToast('ডাটা লোড করতে সমস্যা হয়েছে', 'error');
        });
}

function updateFinancialSummary(summary) {
    if (!summary) return;
    
    document.getElementById('totalRevenue').textContent = '৳' + formatNumber(summary.total_revenue || 0);
    document.getElementById('totalTransactions').textContent = formatNumber(summary.total_transactions || 0);
    document.getElementById('avgTransaction').textContent = '৳' + formatNumber(summary.avg_transaction_value || 0);
    document.getElementById('successRate').textContent = (summary.success_rate || 0).toFixed(1) + '%';
    
    // Update growth rates (sample data for demo)
    document.getElementById('revenueGrowth').textContent = '+15.2%';
    document.getElementById('transactionGrowth').textContent = '+12.8%';
    document.getElementById('avgGrowth').textContent = '+2.4%';
    document.getElementById('rateChange').textContent = '+1.2%';
}

let revenueTrendChart = null;
let paymentMethodChart = null;
let certificateRevenueChart = null;
let predictionChart = null;

function loadRevenueTrendChart() {
    const ctx = document.getElementById('revenueTrendChart').getContext('2d');
    
    revenueTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'রাজস্ব (৳)',
                data: [],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'লেনদেন সংখ্যা',
                data: [],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                fill: false,
                tension: 0.4,
                yAxisID: 'y1'
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
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'রাজস্ব (৳)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '৳' + formatNumber(value);
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'লেনদেন সংখ্যা'
                    },
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
}

function loadPaymentMethodChart() {
    const ctx = document.getElementById('paymentMethodChart').getContext('2d');
    
    paymentMethodChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['bKash', 'Nagad', 'Rocket', 'ব্যাংক', 'নগদ'],
            datasets: [{
                data: [45, 30, 15, 7, 3],
                backgroundColor: [
                    '#e11d48',
                    '#0d9488',
                    '#7c3aed',
                    '#3b82f6',
                    '#64748b'
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

function loadCertificateRevenueChart() {
    const ctx = document.getElementById('certificateRevenueChart').getContext('2d');
    
    certificateRevenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['জন্ম নিবন্ধন', 'মৃত্যু সার্টিফিকেট', 'আয় সার্টিফিকেট', 'চরিত্র সার্টিফিকেট', 'বিবাহ সার্টিফিকেট'],
            datasets: [{
                label: 'রাজস্ব (হাজার ৳)',
                data: [120, 85, 95, 65, 45],
                backgroundColor: '#10b981',
                borderColor: '#059669',
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
                            return '৳' + formatNumber(value * 1000);
                        }
                    }
                }
            }
        }
    });
}

function loadPredictionChart() {
    const ctx = document.getElementById('predictionChart').getContext('2d');
    
    predictionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['বর্তমান মাস', 'পরবর্তী মাস', '২য় মাস', '৩য় মাস'],
            datasets: [{
                label: 'প্রত্যাশিত রাজস্ব',
                data: [120, 135, 148, 160],
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                borderDash: [5, 5]
            }, {
                label: 'ঐতিহাসিক রাজস্ব',
                data: [95, 105, 115, 120],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: false,
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
                        text: 'রাজস্ব (হাজার ৳)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '৳' + formatNumber(value * 1000);
                        }
                    }
                }
            }
        }
    });
}

function updateCharts(data) {
    if (!data) return;
    
    // Update revenue trend chart
    if (revenueTrendChart && data.revenue_trend) {
        const labels = data.revenue_trend.map(item => item.date);
        const revenues = data.revenue_trend.map(item => item.revenue);
        const transactions = data.revenue_trend.map(item => item.transactions);
        
        revenueTrendChart.data.labels = labels;
        revenueTrendChart.data.datasets[0].data = revenues;
        revenueTrendChart.data.datasets[1].data = transactions;
        revenueTrendChart.update();
    }
    
    // Update payment method chart
    if (paymentMethodChart && data.payment_methods) {
        paymentMethodChart.data.datasets[0].data = data.payment_methods.map(method => method.revenue / 1000);
        paymentMethodChart.update();
    }
    
    // Update certificate revenue chart
    if (certificateRevenueChart && data.certificate_revenue) {
        const topCertificates = data.certificate_revenue.slice(0, 5);
        certificateRevenueChart.data.labels = topCertificates.map(cert => cert.name);
        certificateRevenueChart.data.datasets[0].data = topCertificates.map(cert => cert.total_revenue / 1000);
        certificateRevenueChart.update();
    }
}

function updateRevenuePrediction(prediction) {
    if (!prediction) return;
    
    document.getElementById('predictedRevenue').textContent = '৳' + formatNumber(prediction.predicted_revenue || 0);
    document.getElementById('predictionConfidence').textContent = (prediction.confidence || 0) + '% আস্থা';
    document.getElementById('growthRate').textContent = (prediction.growth_rate || 0).toFixed(1) + '%';
    document.getElementById('growthTrend').textContent = prediction.growth_rate > 0 ? 'বর্ধনশীল' : 'হ্রাসমান';
    
    // Update risk assessment
    const riskLevel = prediction.confidence >= 80 ? 'নিম্ন' : 
                     prediction.confidence >= 60 ? 'মাঝারি' : 'উচ্চ';
    document.getElementById('riskLevel').textContent = riskLevel;
    document.getElementById('riskScore').textContent = 'স্কোর: ' + (100 - prediction.confidence) + '/100';
}

function updateKPIs(data) {
    // Update KPI values (sample data for demo)
    document.getElementById('mrr').textContent = '৳' + formatNumber(125000);
    document.getElementById('mrrGrowth').textContent = '+8.5%';
    
    document.getElementById('cac').textContent = '৳' + formatNumber(350);
    document.getElementById('cacTrend').textContent = '-2.1%';
    
    document.getElementById('ltv').textContent = '৳' + formatNumber(4200);
    document.getElementById('ltvGrowth').textContent = '+5.3%';
    
    document.getElementById('ltvCacRatio').textContent = '12:1';
    document.getElementById('ratioStatus').textContent = 'চমৎকার';
    
    // Update progress bars
    document.getElementById('revenueTarget').textContent = '75%';
    document.getElementById('revenueTargetBar').style.width = '75%';
    
    document.getElementById('transactionTarget').textContent = '82%';
    document.getElementById('transactionTargetBar').style.width = '82%';
    
    document.getElementById('successTarget').textContent = '92%';
    document.getElementById('successTargetBar').style.width = '92%';
}

function exportReport(format) {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams();
    
    for (const [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    params.append('format', format);
    
    window.location.href = `{{ route('superadmin.reports.export', 'financial-analytics') }}?${params.toString()}`;
}

function resetFilters() {
    document.getElementById('filterForm').reset();
    document.getElementById('customDateRange').classList.add('hidden');
    loadFinancialAnalyticsData();
}

// Utility functions
function formatNumber(num) {
    return new Intl.NumberFormat('bn-BD').format(num);
}
</script>
@endpush
@endsection