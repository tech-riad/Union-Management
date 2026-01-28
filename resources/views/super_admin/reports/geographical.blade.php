@extends('layouts.super-admin')

@section('title', 'ভৌগলিক রিপোর্ট - ইউনিয়ন ডিজিটাল')

@section('content')
<div class="container-fluid mx-auto px-4">
    <!-- Header Section -->
    <div class="mb-8 animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">ভৌগলিক রিপোর্ট</h1>
                <p class="text-gray-600">ইউনিয়ন এবং ওয়ার্ড অনুযায়ী আবেদন ও রাজস্ব বিশ্লেষণ</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <button onclick="exportReport('csv')" 
                        class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-file-csv mr-2"></i> CSV এক্সপোর্ট
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Unions -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-university text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="totalUnions">0</h3>
                <p class="text-sm text-gray-600">মোট ইউনিয়ন</p>
            </div>
        </div>

        <!-- Total Wards -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-100 rounded-2xl p-6 border border-green-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-600 to-emerald-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="totalWards">0</h3>
                <p class="text-sm text-gray-600">মোট ওয়ার্ড</p>
            </div>
        </div>

        <!-- Total Applications -->
        <div class="bg-gradient-to-r from-purple-50 to-violet-100 rounded-2xl p-6 border border-purple-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-600 to-violet-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="totalApplications">0</h3>
                <p class="text-sm text-gray-600">মোট আবেদন</p>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-r from-orange-50 to-amber-100 rounded-2xl p-6 border border-orange-200 shadow-sm">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-orange-600 to-amber-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1" id="totalRevenue">৳ 0</h3>
                <p class="text-sm text-gray-600">মোট রাজস্ব</p>
            </div>
        </div>
    </div>

    <!-- Geographical Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Union-wise Distribution -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">ইউনিয়ন অনুযায়ী আবেদন</h3>
                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">শীর্ষ ১০</span>
            </div>
            <div class="h-80">
                <canvas id="unionChart"></canvas>
            </div>
        </div>

        <!-- Ward-wise Distribution -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">ওয়ার্ড অনুযায়ী আবেদন</h3>
                <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">শীর্ষ ১০</span>
            </div>
            <div class="h-80">
                <canvas id="wardChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Union Performance Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">ইউনিয়ন পারফরম্যান্স</h3>
            <div class="text-sm text-gray-600">
                মোট <span id="unionCount" class="font-bold">0</span> ইউনিয়ন
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">র্যাঙ্ক</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ইউনিয়ন</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ওয়ার্ড</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">আবেদন</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">রাজস্ব</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">গড় আবেদন/ওয়ার্ড</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">কর্মক্ষমতা</th>
                    </tr>
                </thead>
                <tbody id="unionTable" class="divide-y divide-gray-200">
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
    </div>

    <!-- Ward Performance Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">ওয়ার্ড পারফরম্যান্স</h3>
            <div class="text-sm text-gray-600">
                শীর্ষ <span id="wardCount" class="font-bold">50</span> ওয়ার্ড
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ওয়ার্ড</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ইউনিয়ন</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">আবেদন</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">রাজস্ব</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">গড় আবেদন মান</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">সক্রিয়তা</th>
                    </tr>
                </thead>
                <tbody id="wardTable" class="divide-y divide-gray-200">
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

    <!-- Certificate Type Distribution by Location -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">সার্টিফিকেট টাইপ অনুযায়ী বন্টন</h3>
            <span class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-full">ইউনিয়ন অনুযায়ী</span>
        </div>
        
        <div id="certificateDistribution" class="space-y-6">
            <!-- Certificate distributions will be loaded here -->
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-spinner fa-spin text-xl mb-2"></i>
                <p>ডাটা লোড হচ্ছে...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    loadUnionChart();
    loadWardChart();
    
    // Load geographical data
    loadGeographicalData();
});

function loadGeographicalData() {
    fetch('{{ route("superadmin.reports.geographical") }}?ajax=1')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateSummaryStats(data.data.summary);
                updateUnionTable(data.data.union_stats);
                updateWardTable(data.data.ward_stats);
                updateCertificateDistribution(data.data.certificate_distribution);
                updateCharts(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading geographical data:', error);
            showToast('ডাটা লোড করতে সমস্যা হয়েছে', 'error');
        });
}

function updateSummaryStats(summary) {
    if (!summary) return;
    
    document.getElementById('totalUnions').textContent = formatNumber(summary.total_unions || 0);
    document.getElementById('totalWards').textContent = formatNumber(summary.total_wards || 0);
    document.getElementById('totalApplications').textContent = formatNumber(summary.total_applications_by_location || 0);
    document.getElementById('totalRevenue').textContent = '৳' + formatNumber(summary.total_revenue_by_location || 0);
}

let unionChart = null;
let wardChart = null;

function loadUnionChart() {
    const ctx = document.getElementById('unionChart').getContext('2d');
    
    unionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'আবেদন সংখ্যা',
                data: [],
                backgroundColor: '#3b82f6',
                borderColor: '#2563eb',
                borderWidth: 1
            }, {
                label: 'রাজস্ব (হাজার)',
                data: [],
                backgroundColor: '#10b981',
                borderColor: '#059669',
                borderWidth: 1,
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
                        text: 'আবেদন সংখ্যা'
                    },
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'রাজস্ব (৳)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '৳' + formatNumber(value);
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

function loadWardChart() {
    const ctx = document.getElementById('wardChart').getContext('2d');
    
    wardChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: [],
            datasets: [{
                label: 'আবেদন সংখ্যা',
                data: [],
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
    if (!data || !data.union_stats || !data.ward_stats) return;
    
    // Update union chart
    const topUnions = data.union_stats.slice(0, 10);
    if (unionChart) {
        unionChart.data.labels = topUnions.map(union => union.name);
        unionChart.data.datasets[0].data = topUnions.map(union => union.total_applications);
        unionChart.data.datasets[1].data = topUnions.map(union => union.total_revenue / 1000); // Convert to thousands
        unionChart.update();
    }
    
    // Update ward chart
    const topWards = data.ward_stats.slice(0, 10);
    if (wardChart) {
        wardChart.data.labels = topWards.map(ward => ward.name);
        wardChart.data.datasets[0].data = topWards.map(ward => ward.total_applications);
        wardChart.update();
    }
}

function updateUnionTable(unions) {
    const tableBody = document.getElementById('unionTable');
    const countElement = document.getElementById('unionCount');
    
    if (!unions || unions.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2 text-gray-400"></i>
                    <p class="text-gray-600">কোন ডাটা পাওয়া যায়নি</p>
                </td>
            </tr>
        `;
        countElement.textContent = '0';
        return;
    }
    
    let html = '';
    unions.forEach((union, index) => {
        const rankColors = [
            'bg-gradient-to-r from-yellow-100 to-yellow-200',
            'bg-gradient-to-r from-gray-100 to-gray-200',
            'bg-gradient-to-r from-amber-100 to-amber-200',
            'bg-gradient-to-r from-blue-50 to-blue-100',
            'bg-gradient-to-r from-blue-50 to-blue-100'
        ];
        
        const rankColor = rankColors[index] || 'bg-gray-50';
        const performanceScore = calculateUnionPerformance(union);
        const performanceColor = performanceScore >= 80 ? 'bg-green-100 text-green-800' :
                               performanceScore >= 60 ? 'bg-yellow-100 text-yellow-800' :
                               'bg-red-100 text-red-800';
        
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
                            <i class="fas fa-university"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">${union.name}</div>
                            <div class="text-xs text-gray-500">ID: ${union.id}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">
                        <div class="font-bold text-gray-900">${formatNumber(union.total_wards)}</div>
                        <div class="text-xs text-gray-500">ওয়ার্ড</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">
                        <div class="font-bold text-blue-700">${formatNumber(union.total_applications)}</div>
                        <div class="text-xs text-gray-500">আবেদন</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">
                        <div class="font-bold text-green-700">৳${formatNumber(union.total_revenue)}</div>
                        <div class="text-xs text-gray-500">রাজস্ব</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">
                        <div class="font-bold text-purple-700">${union.avg_applications_per_ward.toFixed(1)}</div>
                        <div class="text-xs text-gray-500">গড়</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-3 py-1 text-xs rounded-full ${performanceColor} font-medium">
                        ${performanceScore}%
                    </span>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
    countElement.textContent = unions.length;
}

function updateWardTable(wards) {
    const tableBody = document.getElementById('wardTable');
    const countElement = document.getElementById('wardCount');
    
    if (!wards || wards.length === 0) {
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
    wards.forEach((ward, index) => {
        const activityScore = calculateWardActivity(ward);
        const activityColor = activityScore >= 80 ? 'bg-green-100 text-green-800' :
                            activityScore >= 60 ? 'bg-yellow-100 text-yellow-800' :
                            'bg-red-100 text-red-800';
        
        const avgApplicationValue = ward.total_applications > 0 ? 
            (ward.total_revenue / ward.total_applications) : 0;
        
        html += `
            <tr class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-green-200 to-green-300 flex items-center justify-center text-green-700 text-sm font-bold mr-2">
                            ${ward.name.substring(0, 2)}
                        </div>
                        <div class="font-medium text-gray-900">${ward.name}</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="text-gray-700">${ward.union_name || 'N/A'}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">
                        <div class="font-bold text-blue-700">${formatNumber(ward.total_applications)}</div>
                        <div class="text-xs text-gray-500">আবেদন</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">
                        <div class="font-bold text-green-700">৳${formatNumber(ward.total_revenue)}</div>
                        <div class="text-xs text-gray-500">রাজস্ব</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-center">
                        <div class="font-bold text-purple-700">৳${avgApplicationValue.toFixed(2)}</div>
                        <div class="text-xs text-gray-500">গড় মান</div>
                    </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-3 py-1 text-xs rounded-full ${activityColor} font-medium">
                        ${activityScore}%
                    </span>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
    countElement.textContent = wards.length;
}

function updateCertificateDistribution(distributions) {
    const container = document.getElementById('certificateDistribution');
    
    if (!distributions || distributions.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-3xl mb-2 text-gray-400"></i>
                <p class="text-gray-600">কোন ডাটা পাওয়া যায়নি</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    distributions.slice(0, 5).forEach(cert => {
        html += `
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-bold text-gray-800">${cert.certificate}</h4>
                    <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                        ${formatNumber(cert.total_applications)} আবেদন
                    </span>
                </div>
                
                <div class="space-y-2">
                    ${cert.union_distribution.slice(0, 5).map(union => `
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">${union.union}</span>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium">${formatNumber(union.count)}</span>
                                <span class="text-sm text-green-700">৳${formatNumber(union.revenue)}</span>
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" 
                                         style="width: ${(union.count / cert.total_applications * 100)}%"></div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
                
                ${cert.union_distribution.length > 5 ? `
                    <div class="mt-3 text-center">
                        <button class="text-sm text-blue-600 hover:text-blue-800">
                            আরও ${cert.union_distribution.length - 5} ইউনিয়ন দেখুন
                        </button>
                    </div>
                ` : ''}
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function calculateUnionPerformance(union) {
    // Simplified performance calculation
    const applicationScore = Math.min((union.total_applications / 1000) * 100, 100);
    const revenueScore = Math.min((union.total_revenue / 500000) * 100, 100);
    const wardUtilization = Math.min((union.avg_applications_per_ward / 50) * 100, 100);
    
    return Math.round((applicationScore * 0.4 + revenueScore * 0.4 + wardUtilization * 0.2));
}

function calculateWardActivity(ward) {
    // Simplified activity calculation
    const applicationScore = Math.min((ward.total_applications / 200) * 100, 100);
    const revenueScore = Math.min((ward.total_revenue / 100000) * 100, 100);
    
    return Math.round((applicationScore * 0.6 + revenueScore * 0.4));
}

function exportReport(format) {
    const params = new URLSearchParams();
    params.append('format', format);
    
    window.location.href = `{{ route('superadmin.reports.export', 'geographical') }}?${params.toString()}`;
}

// Utility functions
function formatNumber(num) {
    return new Intl.NumberFormat('bn-BD').format(num);
}
</script>
@endpush
@endsection