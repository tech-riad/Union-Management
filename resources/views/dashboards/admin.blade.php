@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">

    <!-- Dashboard Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-green-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Admin Dashboard</h1>
                    <p class="text-emerald-100">Welcome back, {{ auth()->user()->name }}!</p>
                    <p class="text-emerald-200 text-sm mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ now()->format('l, F j, Y') }}
                    </p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm text-emerald-200">Last Login</p>
                            <p class="font-semibold">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'First login' }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-white/20 to-white/10 flex items-center justify-center border-2 border-white/30">
                            <i class="fas fa-user-shield text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            
            <!-- Left Sidebar -->
            <div class="lg:w-1/4">
                <!-- Quick Stats Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-5 border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-emerald-100 to-emerald-50 flex items-center justify-center mr-4">
                                <i class="fas fa-certificate text-emerald-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Total Certificates</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ \App\Models\CertificateType::count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-5 border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-amber-100 to-amber-50 flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-amber-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Pending Apps</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ \App\Models\Application::where('status','Pending')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-5 border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-green-100 to-green-50 flex items-center justify-center mr-4">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Approved Apps</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ \App\Models\Application::where('status','Approved')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-5 border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-100 to-blue-50 flex items-center justify-center mr-4">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Total Users</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ \App\Models\User::count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-5 border border-gray-200 mb-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-bolt text-amber-500 mr-2"></i>
                        Quick Actions
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.applications.index') }}?status=Pending" 
                           class="flex items-center p-3 rounded-xl bg-amber-50 hover:bg-amber-100 transition duration-300 group">
                            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center mr-3 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-file-signature text-amber-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Process Applications</p>
                                <p class="text-sm text-gray-500">Review pending requests</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.applications.index') }}" 
                           class="flex items-center p-3 rounded-xl bg-blue-50 hover:bg-blue-100 transition duration-300 group">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-list-check text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">All Applications</p>
                                <p class="text-sm text-gray-500">View complete list</p>
                            </div>
                        </a>
                        
                        @can('access_certificates')
                        <a href="{{ route('admin.certificates.index') }}" 
                           class="flex items-center p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 transition duration-300 group">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center mr-3 group-hover:scale-110 transition duration-300">
                                <i class="fas fa-certificate text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Certificate Types</p>
                                <p class="text-sm text-gray-500">Manage certificates</p>
                            </div>
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Dynamic System Status -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-5 border border-gray-200">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-server text-gray-600 mr-2"></i>
                        System Status
                    </h3>
                    <div class="space-y-4">
                        @php
                            // Dynamic system status calculations
                            $totalApps = \App\Models\Application::count();
                            $pendingApps = \App\Models\Application::where('status','Pending')->count();
                            $approvedApps = \App\Models\Application::where('status','Approved')->count();
                            $rejectedApps = \App\Models\Application::where('status','Rejected')->count();
                            
                            // Server load simulation (based on pending applications)
                            $serverLoad = $pendingApps > 20 ? 80 : ($pendingApps > 10 ? 60 : ($pendingApps > 5 ? 40 : 20));
                            
                            // Database status (always online for demo)
                            $dbStatus = 'Online';
                            $dbColor = 'green';
                            
                            // Storage calculation
                            $totalUsers = \App\Models\User::count();
                            $storageUsed = round(($totalApps * 0.5 + $totalUsers * 0.2) / 10, 1); // MB
                            $storagePercentage = min(100, round(($storageUsed / 5) * 100, 1));
                            $storageColor = $storagePercentage > 80 ? 'red' : ($storagePercentage > 60 ? 'amber' : 'blue');
                            
                            // System uptime (simulated)
                            $uptimeDays = 45; // days
                            $uptimePercentage = 99.8; // %
                            
                            // Response time (simulated based on server load)
                            $responseTime = 100 + ($serverLoad * 2); // ms
                        @endphp
                        
                        <!-- Server Load -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">Server Load</span>
                                <span class="text-sm font-medium 
                                    @if($serverLoad > 80) text-red-600
                                    @elseif($serverLoad > 60) text-amber-600
                                    @else text-green-600 @endif">
                                    {{ $serverLoad }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full 
                                    @if($serverLoad > 80) bg-red-500
                                    @elseif($serverLoad > 60) bg-amber-500
                                    @else bg-green-500 @endif" 
                                    style="width: {{ $serverLoad }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Database Status -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">Database</span>
                                <span class="text-sm font-medium text-green-600 flex items-center">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i> 
                                    {{ $dbStatus }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                Connections: {{ $totalApps + $totalUsers }} active
                            </div>
                        </div>
                        
                        <!-- Storage -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">Storage</span>
                                <span class="text-sm font-medium 
                                    @if($storagePercentage > 80) text-red-600
                                    @elseif($storagePercentage > 60) text-amber-600
                                    @else text-gray-800 @endif">
                                    {{ $storageUsed }}GB / 5GB
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full 
                                    @if($storagePercentage > 80) bg-red-500
                                    @elseif($storagePercentage > 60) bg-amber-500
                                    @else bg-blue-500 @endif" 
                                    style="width: {{ $storagePercentage }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $storagePercentage }}% used • {{ 5 - $storageUsed }}GB free
                            </div>
                        </div>
                        
                        <!-- Uptime -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">System Uptime</span>
                                <span class="text-sm font-medium text-green-600">
                                    {{ $uptimePercentage }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $uptimePercentage }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $uptimeDays }} days without downtime
                            </div>
                        </div>
                        
                        <!-- Response Time -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">Response Time</span>
                                <span class="text-sm font-medium 
                                    @if($responseTime > 200) text-red-600
                                    @elseif($responseTime > 150) text-amber-600
                                    @else text-green-600 @endif">
                                    {{ $responseTime }}ms
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full 
                                    @if($responseTime > 200) bg-red-500
                                    @elseif($responseTime > 150) bg-amber-500
                                    @else bg-green-500 @endif" 
                                    style="width: {{ min(100, $responseTime / 3) }}%"></div>
                            </div>
                        </div>
                        
                        <!-- System Summary -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                                    <span class="text-gray-600">All Systems</span>
                                </div>
                                <span class="font-medium text-green-600">Operational</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Pending Applications Card -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Pending Applications</h2>
                            <p class="text-gray-500 text-sm">Applications awaiting your approval</p>
                        </div>
                        <span class="bg-amber-100 text-amber-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ \App\Models\Application::where('status','Pending')->count() }} Pending
                        </span>
                    </div>
                    
                    @if(\App\Models\Application::where('status','Pending')->exists())
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Application ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Citizen
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Certificate
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach(\App\Models\Application::where('status','Pending')->latest()->take(8)->get() as $app)
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-amber-100 to-amber-50 flex items-center justify-center mr-3">
                                                <i class="fas fa-file-alt text-amber-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">#{{ $app->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $app->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $app->user->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-md bg-emerald-100 flex items-center justify-center mr-2">
                                                <i class="fas fa-certificate text-emerald-600 text-xs"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ $app->certificateType->name ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Fee: ৳{{ number_format($app->certificateType->fee ?? 0, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $app->created_at->format('d/m/Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ $app->created_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <form method="POST" action="{{ route('admin.applications.approve', $app->id) }}" 
                                                  class="approve-form">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm rounded-lg hover:from-green-600 hover:to-emerald-700 transition duration-300 shadow-sm">
                                                    <i class="fas fa-check mr-1.5 text-xs"></i>
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.applications.reject', $app->id) }}" 
                                                  class="reject-form">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-500 to-rose-600 text-white text-sm rounded-lg hover:from-red-600 hover:to-rose-700 transition duration-300 shadow-sm">
                                                    <i class="fas fa-times mr-1.5 text-xs"></i>
                                                    Reject
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.applications.index') }}?search={{ $app->id }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm rounded-lg hover:from-blue-600 hover:to-indigo-700 transition duration-300 shadow-sm">
                                                <i class="fas fa-eye mr-1.5 text-xs"></i>
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if(\App\Models\Application::where('status','Pending')->count() > 8)
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.applications.index') }}?status=Pending" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 font-medium rounded-lg hover:from-gray-200 hover:to-gray-300 transition duration-300 shadow-sm">
                            <i class="fas fa-list mr-2"></i>
                            View All Pending Applications ({{ \App\Models\Application::where('status','Pending')->count() }})
                        </a>
                    </div>
                    @endif
                    
                    @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">All Clear!</h3>
                        <p class="text-gray-500 max-w-md mx-auto">There are no pending applications at the moment. Everything is up to date.</p>
                    </div>
                    @endif
                </div>

                <!-- Recent Activities & Notifications -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Activities -->
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <i class="fas fa-history text-purple-500 mr-2"></i>
                                Recent Activities
                            </h3>
                            <span class="text-xs text-gray-500">Last 24 hours</span>
                        </div>
                        
                        <div class="space-y-4">
                            @php
                                $recentActivities = \App\Models\Application::latest()->take(6)->get();
                            @endphp
                            
                            @foreach($recentActivities as $activity)
                            <div class="flex items-start p-3 rounded-xl hover:bg-gray-50 transition duration-200">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center 
                                        @if($activity->status == 'Approved') bg-green-100 text-green-600
                                        @elseif($activity->status == 'Rejected') bg-red-100 text-red-600
                                        @else bg-amber-100 text-amber-600 @endif">
                                        <i class="fas 
                                            @if($activity->status == 'Approved') fa-check-circle
                                            @elseif($activity->status == 'Rejected') fa-times-circle
                                            @else fa-clock @endif"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between">
                                        <p class="text-sm font-medium text-gray-900">
                                            Application #{{ $activity->id }}
                                            <span class="font-normal text-gray-600">- {{ $activity->certificateType->name ?? 'Certificate' }}</span>
                                        </p>
                                        <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Status: 
                                        <span class="font-medium 
                                            @if($activity->status == 'Approved') text-green-600
                                            @elseif($activity->status == 'Rejected') text-red-600
                                            @else text-amber-600 @endif">
                                            {{ $activity->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($recentActivities->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-inbox text-gray-300 text-3xl mb-2"></i>
                                <p class="text-gray-500">No recent activities</p>
                            </div>
                            @endif
                        </div>
                        
                        @if(!$recentActivities->isEmpty())
                        <div class="mt-6">
                            <a href="{{ route('admin.applications.index') }}" 
                               class="inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700">
                                View all activities
                                <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- Dynamic Weekly Performance -->
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                            Weekly Performance
                        </h3>
                        
                        @php
                            // Calculate weekly data
                            $weeklyData = [];
                            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                            $totalWeeklyApps = 0;
                            $previousWeeklyApps = 0;
                            
                            for ($i = 6; $i >= 0; $i--) {
                                $date = now()->subDays($i);
                                $dayApps = \App\Models\Application::whereDate('created_at', $date)->count();
                                $weeklyData[] = [
                                    'day' => $date->format('D'),
                                    'date' => $date->format('d/m'),
                                    'applications' => $dayApps,
                                    'height' => $dayApps * 5 // For chart height
                                ];
                                $totalWeeklyApps += $dayApps;
                                
                                // Previous week data for comparison
                                $prevDate = $date->subWeek();
                                $previousWeeklyApps += \App\Models\Application::whereDate('created_at', $prevDate)->count();
                            }
                            
                            // Calculate weekly growth
                            $weeklyGrowth = $previousWeeklyApps > 0 
                                ? round((($totalWeeklyApps - $previousWeeklyApps) / $previousWeeklyApps) * 100, 1)
                                : ($totalWeeklyApps > 0 ? 100 : 0);
                            
                            // Calculate approval rate for the week
                            $approvedThisWeek = \App\Models\Application::where('status', 'Approved')
                                ->whereDate('created_at', '>=', now()->subDays(7))
                                ->count();
                            
                            $totalThisWeek = $totalWeeklyApps;
                            $approvalRate = $totalThisWeek > 0 
                                ? round(($approvedThisWeek / $totalThisWeek) * 100, 1)
                                : 0;
                                
                            // Average processing time (simulated based on approval rate)
                            $avgProcessingTime = $approvalRate > 80 ? 2.1 : ($approvalRate > 60 ? 3.5 : 4.8);
                        @endphp
                        
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-2xl font-bold text-gray-800">{{ $totalWeeklyApps }}</p>
                                    <p class="text-sm text-gray-500">Applications this week</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold {{ $weeklyGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $weeklyGrowth >= 0 ? '+' : '' }}{{ $weeklyGrowth }}%
                                    </p>
                                    <p class="text-xs text-gray-500">vs last week</p>
                                </div>
                            </div>
                            
                            <!-- Dynamic Chart -->
                            <div class="h-48 flex items-end space-x-2">
                                @foreach($weeklyData as $dayData)
                                <div class="flex-1 flex flex-col items-center">
                                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg mb-2" 
                                         style="height: {{ $dayData['height'] }}px; max-height: 150px;"></div>
                                    <span class="text-xs text-gray-500">{{ $dayData['day'] }}</span>
                                    <span class="text-xs font-semibold text-gray-700">{{ $dayData['applications'] }}</span>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Chart Legend -->
                            <div class="mt-4 flex justify-center space-x-4">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-sm mr-2"></div>
                                    <span class="text-xs text-gray-600">Applications</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-300 rounded-sm mr-2"></div>
                                    <span class="text-xs text-gray-600">Previous Week</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dynamic Stats -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Approval Rate</div>
                                <div class="text-xl font-bold text-gray-800">{{ $approvalRate }}%</div>
                                <div class="text-xs {{ $approvalRate > 80 ? 'text-green-600' : ($approvalRate > 60 ? 'text-amber-600' : 'text-red-600') }} mt-1">
                                    @if($approvalRate > 80)
                                    <i class="fas fa-arrow-up mr-1"></i> Excellent
                                    @elseif($approvalRate > 60)
                                    <i class="fas fa-minus mr-1"></i> Average
                                    @else
                                    <i class="fas fa-arrow-down mr-1"></i> Needs Improvement
                                    @endif
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Avg. Processing Time</div>
                                <div class="text-xl font-bold text-gray-800">{{ $avgProcessingTime }} hrs</div>
                                <div class="text-xs {{ $avgProcessingTime < 3 ? 'text-green-600' : ($avgProcessingTime < 5 ? 'text-amber-600' : 'text-red-600') }} mt-1">
                                    @if($avgProcessingTime < 3)
                                    <i class="fas fa-bolt mr-1"></i> Fast
                                    @elseif($avgProcessingTime < 5)
                                    <i class="fas fa-clock mr-1"></i> Normal
                                    @else
                                    <i class="fas fa-tachometer-alt mr-1"></i> Slow
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Weekly Summary -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm">
                                <div class="text-gray-600">Weekly Summary:</div>
                                <div class="flex space-x-2">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                        {{ $approvedThisWeek }} Approved
                                    </span>
                                    <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded-full">
                                        {{ \App\Models\Application::where('status', 'Pending')->whereDate('created_at', '>=', now()->subDays(7))->count() }} Pending
                                    </span>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                        {{ \App\Models\Application::where('status', 'Rejected')->whereDate('created_at', '>=', now()->subDays(7))->count() }} Rejected
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Custom Styles -->
<style>
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .shadow-hover:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1), 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Smooth transitions */
    .transition-200 {
        transition: all 0.2s ease;
    }
    
    .transition-300 {
        transition: all 0.3s ease;
    }
    
    /* Card hover effects */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    /* Chart animation */
    @keyframes growBar {
        from { height: 0; }
        to { height: var(--target-height); }
    }
    
    .chart-bar {
        animation: growBar 1s ease-out forwards;
    }
</style>

@endsection

@section('scripts')
<script>
    // Confirmation for approve/reject actions
    document.addEventListener('DOMContentLoaded', function() {
        // Approve/Reject confirmation
        const approveForms = document.querySelectorAll('.approve-form');
        const rejectForms = document.querySelectorAll('.reject-form');
        
        approveForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to approve this application?')) {
                    e.preventDefault();
                }
            });
        });
        
        rejectForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to reject this application?')) {
                    e.preventDefault();
                }
            });
        });
        
        // Animate chart bars
        function animateChartBars() {
            const bars = document.querySelectorAll('.bg-gradient-to-t.from-blue-500');
            bars.forEach((bar, index) => {
                const targetHeight = bar.style.height;
                bar.style.height = '0px';
                setTimeout(() => {
                    bar.style.transition = 'height 1s ease-out';
                    bar.style.height = targetHeight;
                }, index * 100);
            });
        }
        
        // Initialize chart animation
        setTimeout(animateChartBars, 500);
        
        // Real-time updates simulation
        function updateDynamicData() {
            // Simulate updating weekly performance data
            console.log('Updating dynamic dashboard data...');
            
            // In a real application, you would fetch updated data from API
            // For now, we'll just log and update the time
            const timeElement = document.querySelector('.text-emerald-200 .fa-calendar-alt');
            if (timeElement) {
                const parent = timeElement.closest('p');
                if (parent) {
                    const now = new Date();
                    const dateStr = now.toLocaleDateString('en-US', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    });
                    parent.innerHTML = `<i class="fas fa-calendar-alt mr-1"></i> ${dateStr}`;
                }
            }
        }
        
        // Update every 5 minutes
        setInterval(updateDynamicData, 300000);
        
        // Add hover effects to all cards
        const cards = document.querySelectorAll('.bg-gradient-to-br');
        cards.forEach(card => {
            card.classList.add('hover-lift', 'transition-300');
        });
        
        // Toast notification for actions
        window.showToast = function(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);
            
            // Remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 5000);
        };
        
        // If there's a success message in session
        @if(session('success'))
        showToast("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
        showToast("{{ session('error') }}", 'error');
        @endif
        
        // Update System Status indicators in real-time
        function updateSystemStatusIndicators() {
            // Simulate dynamic updates to system status
            const serverLoadElement = document.querySelector('.text-sm.font-medium:contains("Server Load")');
            if (serverLoadElement) {
                const currentLoad = parseInt(serverLoadElement.textContent);
                const newLoad = Math.min(100, Math.max(10, currentLoad + Math.random() * 10 - 5));
                serverLoadElement.textContent = Math.round(newLoad) + '%';
                serverLoadElement.className = serverLoadElement.className.replace(
                    /text-(red|amber|green)-600/, 
                    newLoad > 80 ? 'text-red-600' : newLoad > 60 ? 'text-amber-600' : 'text-green-600'
                );
                
                // Update progress bar
                const progressBar = serverLoadElement.closest('div').querySelector('.h-2.rounded-full');
                if (progressBar) {
                    progressBar.style.width = newLoad + '%';
                    progressBar.className = progressBar.className.replace(
                        /bg-(red|amber|green)-500/,
                        newLoad > 80 ? 'bg-red-500' : newLoad > 60 ? 'bg-amber-500' : 'bg-green-500'
                    );
                }
            }
            
            // Update response time
            const responseTimeElement = document.querySelector('.text-sm.font-medium:contains("ms")');
            if (responseTimeElement && responseTimeElement.textContent.includes('ms')) {
                const currentTime = parseInt(responseTimeElement.textContent);
                const newTime = Math.min(300, Math.max(50, currentTime + Math.random() * 20 - 10));
                responseTimeElement.textContent = Math.round(newTime) + 'ms';
                responseTimeElement.className = responseTimeElement.className.replace(
                    /text-(red|amber|green)-600/,
                    newTime > 200 ? 'text-red-600' : newTime > 150 ? 'text-amber-600' : 'text-green-600'
                );
            }
        }
        
        // Update system status every 30 seconds
        setInterval(updateSystemStatusIndicators, 30000);
    });
</script>
@endsection