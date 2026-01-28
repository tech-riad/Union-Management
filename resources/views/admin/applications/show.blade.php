@extends('layouts.admin')

@section('title', 'Application Details')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    </li>
    <li class="inline-flex items-center">
        <a href="{{ route('admin.applications.index') }}" 
           class="text-sm font-medium text-gray-500 hover:text-blue-600">
            Applications
        </a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    </li>
    <li class="inline-flex items-center">
        <span class="text-sm font-medium text-gray-700">#{{ $application->id }}</span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Application Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Application Details</h1>
                <div class="flex items-center space-x-4 text-sm">
                    <span class="text-gray-600">ID: <span class="font-semibold">#{{ $application->id }}</span></span>
                    <span class="text-gray-400">•</span>
                    <span class="text-gray-600">Applied: <span class="font-semibold">{{ \Carbon\Carbon::parse($application->created_at)->format('d M, Y h:i A') }}</span></span>
                </div>
            </div>
            
            <!-- Status Badge -->
            @if($application->status === 'Approved')
                <span class="px-4 py-2 inline-flex items-center text-sm font-semibold rounded-full bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-2"></i> Approved
                </span>
            @elseif($application->status === 'Rejected')
                <span class="px-4 py-2 inline-flex items-center text-sm font-semibold rounded-full bg-red-100 text-red-800">
                    <i class="fas fa-times-circle mr-2"></i> Rejected
                </span>
            @else
                <span class="px-4 py-2 inline-flex items-center text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    <i class="fas fa-clock mr-2"></i> Pending
                </span>
            @endif
        </div>
        
        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-3 mb-6">
            @if($application->status === 'Pending')
                <form action="{{ route('admin.applications.approve', $application->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                        <i class="fas fa-check mr-2"></i> Approve Application
                    </button>
                </form>
                
                <button type="button" 
                        onclick="openRejectModal()"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i> Reject Application
                </button>
            @endif
            
            <a href="{{ route('admin.applications.index') }}" 
               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
            
            @if($application->status === 'Approved')
                <a href="{{ route('admin.applications.certificate.pdf', $application->id) }}" 
                   target="_blank"
                   class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200 font-medium">
                    <i class="fas fa-file-pdf mr-2"></i> View Certificate
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Applicant Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                        Applicant Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Full Name (Bangla)</label>
                            <p class="text-gray-800 font-medium">{{ $formData['name_bangla'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Full Name (English)</label>
                            <p class="text-gray-800 font-medium">{{ $formData['name_english'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Father's Name</label>
                            <p class="text-gray-800">{{ $formData['father_name_bangla'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Mother's Name</label>
                            <p class="text-gray-800">{{ $formData['mother_name_bangla'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Date of Birth</label>
                            <p class="text-gray-800">{{ $formData['dob'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Gender</label>
                            <p class="text-gray-800">{{ $formData['gender'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">NID Number</label>
                            <p class="text-gray-800">{{ $formData['nid_number'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Mobile Number</label>
                            <p class="text-gray-800">{{ $formData['mobile'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Email Address</label>
                            <p class="text-gray-800">{{ $formData['email'] ?? ($application->user->email ?? 'N/A') }}</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Present Address</label>
                            <p class="text-gray-800">{{ $formData['present_address'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Permanent Address</label>
                            <p class="text-gray-800">{{ $formData['permanent_address'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>
                        Additional Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Religion</label>
                            <p class="text-gray-800">{{ $formData['religion'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Marital Status</label>
                            <p class="text-gray-800">{{ $formData['marital_status'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Education</label>
                            <p class="text-gray-800">{{ $formData['education'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Occupation</label>
                            <p class="text-gray-800">{{ $formData['occupation'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Height</label>
                            <p class="text-gray-800">{{ $formData['height'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Birth Mark</label>
                            <p class="text-gray-800">{{ $formData['birth_mark'] ?? 'N/A' }}</p>
                        </div>
                        
                        @if(isset($formData['other_details']) && !empty($formData['other_details']))
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Other Details</label>
                            <p class="text-gray-800 whitespace-pre-line">{{ $formData['other_details'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Application Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-file-alt text-purple-500 mr-2"></i>
                        Application Summary
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Certificate Type</label>
                        <p class="text-gray-800 font-medium">
                            {{ $application->certificateType->name ?? 'N/A' }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Application Fee</label>
                        <p class="text-2xl font-bold text-green-600">৳{{ number_format($application->fee, 2) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Payment Status</label>
                        @if($application->payment_status === 'paid')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check mr-2"></i> Paid
                            </span>
                            @if($application->paid_at)
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($application->paid_at)->format('d M, Y') }}
                                </p>
                            @endif
                        @else
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times mr-2"></i> Unpaid
                            </span>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Applied By User</label>
                        <div class="flex items-center mt-2">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-medium">
                                    {{ substr($application->user->name ?? 'U', 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $application->user->name ?? 'User Not Found' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $application->user->email ?? 'No email' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-history text-orange-500 mr-2"></i>
                        Timeline
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-paper-plane text-blue-600 text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($application->created_at)->format('d M, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                        
                        @if($application->status === 'Approved')
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Application Approved</p>
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($application->approved_at)->format('d M, Y h:i A') }}
                                    </p>
                                    @if($application->approved_by)
                                        <p class="text-xs text-gray-500">
                                            By: Admin {{ $application->approved_by }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($application->certificate_number)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                        <i class="fas fa-certificate text-purple-600 text-sm"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Certificate Generated</p>
                                        <p class="text-xs text-gray-500">
                                            Certificate #: {{ $application->certificate_number }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @elseif($application->status === 'Rejected')
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Application Rejected</p>
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($application->rejected_at)->format('d M, Y h:i A') }}
                                    </p>
                                    @if($application->rejected_by)
                                        <p class="text-xs text-gray-500">
                                            By: Admin {{ $application->rejected_by }}
                                        </p>
                                    @endif
                                    @if($application->rejection_reason)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Reason: {{ $application->rejection_reason }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Pending Review</p>
                                    <p class="text-xs text-gray-500">Waiting for admin approval</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-database text-gray-500 mr-2"></i>
                        System Information
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Application ID</span>
                        <span class="text-sm font-medium text-gray-900">#{{ $application->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">User ID</span>
                        <span class="text-sm font-medium text-gray-900">{{ $application->user_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Certificate Type ID</span>
                        <span class="text-sm font-medium text-gray-900">{{ $application->certificate_type_id }}</span>
                    </div>
                    @if($application->invoice_id)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Invoice ID</span>
                        <span class="text-sm font-medium text-gray-900">{{ $application->invoice_id }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($application->updated_at)->format('d M, Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Reject Application</h3>
            </div>
            <form action="{{ route('admin.applications.reject', $application->id) }}" method="POST">
                @csrf
                <div class="px-6 py-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea name="rejection_reason" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Please provide a reason for rejecting this application..."
                              required></textarea>
                </div>
                <div class="px-6 py-4 border-t flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeRejectModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRejectModal();
        }
    });
    
    // Close modal when clicking outside
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target.id === 'rejectModal') {
            closeRejectModal();
        }
    });
</script>
@endpush