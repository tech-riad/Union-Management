@extends('layouts.super-admin')

@section('title', 'Application Details')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-blue-50">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Application Details</h2>
                <p class="text-gray-600 text-sm mt-1">
                    ID: #{{ $application->id }}
                    <span class="mx-2">•</span>
                    Status: {{ ucfirst($application->status) }}
                    <span class="mx-2">•</span>
                    Payment: {{ ucfirst($application->payment_status) }}
                </p>
            </div>
            <a href="{{ route('super_admin.applications.index') }}" class="btn-back">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Application Details -->
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Basic Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Applicant Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Applicant Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $application->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $application->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">User ID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $application->user_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Applied Date</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $application->created_at->format('d M, Y h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Certificate Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-file-certificate text-green-600 mr-2"></i>
                        Certificate Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Certificate Type</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $application->certificateType->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fee</label>
                            <p class="mt-1 text-sm font-medium text-green-600">
                                ৳{{ number_format($application->fee, 2) }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Certificate Number</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $application->certificate_number ?? 'Not assigned' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Certificate ID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $application->certificate_type_id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Data -->
                @if(count($formData) > 0)
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-edit text-purple-600 mr-2"></i>
                        Application Form Data
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($formData as $key => $value)
                            @if(!empty($value))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 capitalize">
                                    {{ str_replace('_', ' ', $key) }}
                                </label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if(is_array($value))
                                        {{ implode(', ', $value) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </p>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Actions & Status -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Application Status
                    </h3>
                    
                    <!-- Current Status -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                        @if($application->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i> Approved
                            </span>
                            @if($application->approved_at)
                                <p class="mt-2 text-sm text-gray-600">
                                    Approved on: {{ $application->approved_at->format('d M, Y') }}
                                </p>
                                @if($application->approvedBy)
                                    <p class="text-sm text-gray-600">
                                        By: {{ $application->approvedBy->name }}
                                    </p>
                                @endif
                            @endif
                        @elseif($application->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-2"></i> Rejected
                            </span>
                            @if($application->rejected_at)
                                <p class="mt-2 text-sm text-gray-600">
                                    Rejected on: {{ $application->rejected_at->format('d M, Y') }}
                                </p>
                                @if($application->rejectedBy)
                                    <p class="text-sm text-gray-600">
                                        By: {{ $application->rejectedBy->name }}
                                    </p>
                                @endif
                                @if($application->rejection_reason)
                                    <p class="mt-2 text-sm text-gray-600">
                                        Reason: {{ $application->rejection_reason }}
                                    </p>
                                @endif
                            @endif
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-2"></i> Pending
                            </span>
                        @endif
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                        @if($application->payment_status === 'paid')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-2"></i> Paid
                            </span>
                            @if($application->paid_at)
                                <p class="mt-2 text-sm text-gray-600">
                                    Paid on: {{ $application->paid_at->format('d M, Y') }}
                                </p>
                            @endif
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times mr-2"></i> Unpaid
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-cogs text-gray-600 mr-2"></i>
                        Actions
                    </h3>
                    
                    <div class="space-y-3">
                        @if($application->status === 'pending')
                            <!-- Approve Button -->
                            <form action="{{ route('super_admin.applications.approve', $application->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-check mr-2"></i> Approve Application
                                </button>
                            </form>

                            <!-- Reject Button with Modal -->
                            <button type="button" 
                                    onclick="showRejectModal()"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-times mr-2"></i> Reject Application
                            </button>
                        @endif

                        <!-- Payment Action -->
                        @if($application->payment_status === 'unpaid')
                            <form action="{{ route('super_admin.applications.payment', $application->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <i class="fas fa-money-bill-wave mr-2"></i> Mark as Paid
                                </button>
                            </form>
                        @endif

                        <!-- Generate PDF -->
                        @if($application->status === 'approved')
                            <a href="{{ route('certificates.pdf', $application->id) }}" 
                               target="_blank"
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                <i class="fas fa-file-pdf mr-2"></i> Generate Certificate PDF
                            </a>
                        @endif

                        <!-- Edit Application -->
                        <a href="#" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-edit mr-2"></i> Edit Application
                        </a>
                    </div>
                </div>

                <!-- Invoice Information -->
                @if($application->invoice)
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-file-invoice text-blue-600 mr-2"></i>
                        Invoice Details
                    </h3>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Invoice Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $application->invoice->invoice_no }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <p class="mt-1 text-sm font-medium text-green-600">
                                ৳{{ number_format($application->invoice->amount, 2) }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $application->invoice->payment_method ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <a href="{{ route('citizen.invoices.show', $application->invoice->id) }}" 
                               target="_blank"
                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900">
                                <i class="fas fa-external-link-alt mr-1"></i> View Invoice
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Application</h3>
        <form action="{{ route('super_admin.applications.reject', $application->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700">
                    Rejection Reason
                </label>
                <textarea id="rejection_reason" 
                          name="rejection_reason" 
                          rows="4"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                          required></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="hideRejectModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Confirm Reject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endpush