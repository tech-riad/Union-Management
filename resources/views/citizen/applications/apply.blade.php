@extends('layouts.app')

@section('title', 'সার্টিফিকেট আবেদন')

@push('styles')
<style>
    /* Modern Color Theme */
    :root {
        --primary-blue: #3b82f6;
        --primary-blue-dark: #1d4ed8;
        --primary-blue-light: #dbeafe;
        
        --primary-green: #10b981;
        --primary-green-dark: #059669;
        --primary-green-light: #d1fae5;
        
        --primary-orange: #f97316;
        --primary-orange-dark: #ea580c;
        --primary-orange-light: #ffedd5;
        
        --accent-blue: #60a5fa;
        --accent-green: #34d399;
        --accent-orange: #fb923c;
        
        --dark: #1f2937;
        --light: #f9fafb;
        --gray: #6b7280;
        --gray-light: #e5e7eb;
        --white: #ffffff;
    }
    
    /* Background Gradient */
    .bg-gradient-modern {
        background: linear-gradient(135deg, #f0f9ff 0%, #f0fdf4 100%);
        min-height: 100vh;
    }
    
    /* Modern Card */
    .modern-card {
        background: var(--white);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--gray-light);
        transition: all 0.3s ease;
    }
    
    .modern-card:hover {
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
    }
    
    /* Progress Tabs - Modern */
    .progress-tabs {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3rem;
        position: relative;
    }
    
    .progress-tabs::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--gray-light);
        z-index: 1;
    }
    
    .progress-line {
        position: absolute;
        top: 24px;
        left: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-blue), var(--primary-green));
        transition: all 0.4s ease;
        z-index: 2;
    }
    
    .progress-step {
        position: relative;
        z-index: 3;
        text-align: center;
        flex: 1;
    }
    
    .step-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--white);
        border: 2px solid var(--gray-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-weight: 600;
        color: var(--gray);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .step-circle::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .step-circle i {
        position: relative;
        z-index: 1;
        transition: color 0.3s ease;
    }
    
    .progress-step.active .step-circle {
        border-color: transparent;
    }
    
    .progress-step.active .step-circle::before {
        opacity: 1;
    }
    
    .progress-step.active .step-circle i {
        color: var(--white);
    }
    
    .progress-step.completed .step-circle {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
        border-color: transparent;
        color: var(--white);
    }
    
    .step-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray);
        transition: all 0.3s ease;
    }
    
    .progress-step.active .step-label {
        color: var(--primary-blue);
        font-weight: 600;
    }
    
    /* Certificate Grid */
    .certificates-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    @media (min-width: 640px) {
        .certificates-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (min-width: 1024px) {
        .certificates-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    /* Certificate Card */
    .certificate-card-modern {
        background: var(--white);
        border-radius: 16px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    
    .certificate-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.15);
    }
    
    .certificate-card-modern.selected {
        border-color: var(--primary-blue);
        background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%);
    }
    
    .certificate-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue), var(--primary-green));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .certificate-card-modern.selected::before {
        opacity: 1;
    }
    
    .certificate-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.25rem;
        background: linear-gradient(135deg, var(--primary-blue-light), var(--primary-green-light));
        color: var(--primary-blue);
    }
    
    .certificate-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }
    
    .certificate-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-light);
    }
    
    .certificate-fee {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-green);
    }
    
    .certificate-fee span {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray);
        margin-left: 0.25rem;
    }
    
    .certificate-validity {
        font-size: 0.875rem;
        color: var(--gray);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    /* Form Elements */
    .form-group-modern {
        margin-bottom: 1.5rem;
    }
    
    .form-label-modern {
        display: block;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    
    .form-label-modern.required::after {
        content: " *";
        color: #ef4444;
    }
    
    .form-input-modern {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--gray-light);
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--white);
        color: var(--dark);
    }
    
    .form-input-modern:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Buttons */
    .btn-modern {
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-modern:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
        color: var(--white);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }
    
    .btn-primary-modern:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary-modern {
        background: var(--white);
        color: var(--dark);
        border: 2px solid var(--gray-light);
    }
    
    .btn-secondary-modern:hover:not(:disabled) {
        background: var(--light);
        border-color: var(--primary-blue-light);
    }
    
    /* Wizard Steps */
    .wizard-step {
        display: none;
        animation: fadeIn 0.5s ease;
    }
    
    .wizard-step.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Navigation Buttons */
    .nav-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 2rem;
        margin-top: 2rem;
        border-top: 1px solid var(--gray-light);
    }
    
    /* Rules Box */
    .rules-box {
        background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--primary-blue-light);
    }
    
    .rule-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(59, 130, 246, 0.1);
    }
    
    .rule-item:last-child {
        border-bottom: none;
    }
    
    .rule-icon {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--white);
        color: var(--primary-blue);
        font-size: 0.875rem;
    }
    
    /* Agreement Checkbox */
    .agreement-checkbox {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        background: var(--light);
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .checkbox-container {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }
    
    .checkbox-container input[type="checkbox"] {
        display: none;
    }
    
    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid var(--gray-light);
        border-radius: 4px;
        margin-right: 12px;
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .checkmark:after {
        content: "✓";
        position: absolute;
        color: var(--white);
        font-size: 12px;
        font-weight: bold;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .checkbox-container input:checked ~ .checkmark {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
        border-color: transparent;
    }
    
    .checkbox-container input:checked ~ .checkmark:after {
        opacity: 1;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .modern-card {
            padding: 1.5rem;
        }
        
        .progress-tabs {
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .progress-step {
            flex: 0 0 calc(50% - 0.5rem);
        }
        
        .progress-tabs::before,
        .progress-line {
            display: none;
        }
    }
    
    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 24px;
        right: 24px;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        color: var(--white);
        font-weight: 500;
        z-index: 9999;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        animation: slideInRight 0.3s ease;
    }
    
    .toast-success {
        background: linear-gradient(135deg, var(--primary-green), var(--accent-green));
    }
    
    .toast-error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    
    .toast-warning {
        background: linear-gradient(135deg, var(--primary-orange), var(--accent-orange));
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endpush

@section('content')
<div class="bg-gradient-modern">
    <div class="max-w-6xl mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-green-600">
                    সার্টিফিকেট আবেদন
                </span>
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                ডিজিটাল পদ্ধতিতে সহজেই সার্টিফিকেটের জন্য আবেদন করুন
            </p>
        </div>
        
        <!-- Progress Steps -->
        <div class="modern-card mb-8">
            <div class="progress-tabs">
                <div class="progress-line" id="progressLine"></div>
                
                <div class="progress-step active" id="stepIndicator-1">
                    <div class="step-circle">
                        <i class="fas fa-scroll"></i>
                    </div>
                    <div class="step-label">নিয়মাবলী</div>
                </div>
                
                <div class="progress-step" id="stepIndicator-2">
                    <div class="step-circle">
                        <i class="fas fa-file-certificate"></i>
                    </div>
                    <div class="step-label">সার্টিফিকেট</div>
                </div>
                
                <div class="progress-step" id="stepIndicator-3">
                    <div class="step-circle">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="step-label">বিস্তারিত</div>
                </div>
                
                <div class="progress-step" id="stepIndicator-4">
                    <div class="step-circle">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="step-label">জমা দিন</div>
                </div>
            </div>
        </div>
        
        <!-- Form Container -->
        <div class="modern-card">
            <form id="wizardForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="selectedCertificateId" name="certificate_id" value="{{ $certificate->id }}">
                
                <!-- Step 1: Rules -->
                <div class="wizard-step active" id="step1">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            <i class="fas fa-scroll text-blue-600 mr-2"></i>
                            সরকারী নিয়মাবলী
                        </h2>
                        <p class="text-gray-600">
                            আবেদন করার আগে নিচের নিয়মাবলী মনোযোগ সহকারে পড়ুন
                        </p>
                    </div>
                    
                    <div class="rules-box">
                        <div class="rule-item">
                            <div class="rule-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">সঠিক তথ্য প্রদান</h4>
                                <p class="text-gray-600 text-sm">আবেদনটি অবশ্যই সত্য ও নির্ভুল তথ্যের উপর ভিত্তি করে হতে হবে। ভুল তথ্য প্রদান করলে আবেদন বাতিল বা আইনগত ব্যবস্থা গ্রহণ করা হতে পারে।</p>
                            </div>
                        </div>
                        
                        <div class="rule-item">
                            <div class="rule-icon">
                                <i class="fas fa-file-circle-check"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">একক আবেদন নীতি</h4>
                                <p class="text-gray-600 text-sm">একই সার্টিফিকেটের জন্য একাধিকবার আবেদন করা যাবে না। প্রতিটি সার্টিফিকেটের জন্য শুধুমাত্র একটি আবেদন গ্রহণযোগ্য হবে।</p>
                            </div>
                        </div>
                        
                        <div class="rule-item">
                            <div class="rule-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">প্রক্রিয়াকরণ সময়</h4>
                                <p class="text-gray-600 text-sm">আবেদন জমা দেওয়ার পর নির্ধারিত সময়ের মধ্যে প্রক্রিয়াকরণ সম্পন্ন হবে। প্রয়োজনে অতিরিক্ত তথ্য বা নথির জন্য যোগাযোগ করা হতে পারে।</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="agreement-checkbox">
                        <label class="checkbox-container">
                            <input type="checkbox" id="agreeRules" name="agree_rules" value="1">
                            <span class="checkmark"></span>
                            <span class="text-gray-800 font-medium">
                                আমি উপরের সব নিয়মাবলী পড়েছি, বুঝেছি এবং মানতে সম্মত আছি
                            </span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" id="next1" class="btn-modern btn-primary-modern" disabled>
                            <span>পরবর্তী ধাপ</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Certificate Selection -->
                <div class="wizard-step" id="step2">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            <i class="fas fa-file-certificate text-orange-600 mr-2"></i>
                            সার্টিফিকেট নির্বাচন
                        </h2>
                        <p class="text-gray-600">
                            আপনার প্রয়োজন অনুযায়ী সার্টিফিকেট নির্বাচন করুন
                        </p>
                    </div>
                    
                    <div class="certificates-grid">
                        @php
                            // Get form fields for selected certificate
                            $selectedFormFields = [];
                            if ($certificate->form_fields) {
                                if (is_string($certificate->form_fields)) {
                                    $selectedFormFields = json_decode($certificate->form_fields, true);
                                } else {
                                    $selectedFormFields = $certificate->form_fields;
                                }
                            }
                            $selectedFieldCount = is_array($selectedFormFields) ? count($selectedFormFields) : 0;
                        @endphp
                        
                        <!-- Show selected certificate -->
                        <div class="certificate-card-modern selected"
                             data-id="{{ $certificate->id }}"
                             data-certificate-id="{{ $certificate->id }}"
                             data-fields='@json($selectedFormFields)'>
                            
                            <div class="certificate-icon">
                                <i class="fas fa-file-certificate"></i>
                            </div>
                            
                            <h3 class="certificate-name">{{ $certificate->name }}</h3>
                            
                            @if($certificate->description)
                                <p class="text-gray-600 text-sm mb-3">{{ $certificate->description }}</p>
                            @endif
                            
                            <div class="certificate-details">
                                <div class="certificate-fee">
                                    {{ number_format($certificate->fee, 2) }}
                                    <span>টাকা</span>
                                </div>
                                @if($certificate->validity)
                                <div class="certificate-validity">
                                    <i class="fas fa-calendar-check"></i>
                                    {{ $certificate->validity }}
                                </div>
                                @endif
                            </div>
                            
                            @if($selectedFieldCount > 0)
                            <div class="absolute top-3 right-3 text-xs font-semibold bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                {{ $selectedFieldCount }} ফিল্ড
                            </div>
                            @endif
                        </div>
                        
                        <!-- Show other certificates if any -->
                        @foreach($allCertificates as $otherCertificate)
                            @if($otherCertificate->id != $certificate->id)
                                @php
                                    $formFields = [];
                                    if ($otherCertificate->form_fields) {
                                        if (is_string($otherCertificate->form_fields)) {
                                            $formFields = json_decode($otherCertificate->form_fields, true);
                                        } else {
                                            $formFields = $otherCertificate->form_fields;
                                        }
                                    }
                                    $fieldCount = is_array($formFields) ? count($formFields) : 0;
                                @endphp
                                
                                <div class="certificate-card-modern certificate-card"
                                     data-id="{{ $otherCertificate->id }}"
                                     data-certificate-id="{{ $otherCertificate->id }}"
                                     data-fields='@json($formFields)'>
                                    
                                    <div class="certificate-icon">
                                        <i class="fas fa-file-certificate"></i>
                                    </div>
                                    
                                    <h3 class="certificate-name">{{ $otherCertificate->name }}</h3>
                                    
                                    @if($otherCertificate->description)
                                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($otherCertificate->description, 80) }}</p>
                                    @endif
                                    
                                    <div class="certificate-details">
                                        <div class="certificate-fee">
                                            {{ number_format($otherCertificate->fee, 2) }}
                                            <span>টাকা</span>
                                        </div>
                                        @if($otherCertificate->validity)
                                        <div class="certificate-validity">
                                            <i class="fas fa-calendar-check"></i>
                                            {{ $otherCertificate->validity }}
                                        </div>
                                        @endif
                                    </div>
                                    
                                    @if($fieldCount > 0)
                                    <div class="absolute top-3 right-3 text-xs font-semibold bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        {{ $fieldCount }} ফিল্ড
                                    </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                    
                    @if(count($allCertificates) === 0)
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto mb-6 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-folder-open text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-3">কোন সার্টিফিকেট পাওয়া যায়নি</h3>
                            <p class="text-gray-500 mb-8">বর্তমানে কোন সার্টিফিকেটের জন্য আবেদন গ্রহণ করা হচ্ছে না</p>
                        </div>
                    @endif
                    
                    <div class="nav-buttons">
                        <button type="button" id="prev2" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i>
                            <span>পূর্ববর্তী</span>
                        </button>
                        <button type="button" id="next2" class="btn-modern btn-primary-modern">
                            <span>পরবর্তী ধাপ</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Form Details -->
                <div class="wizard-step" id="step3">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            <i class="fas fa-edit text-green-600 mr-2"></i>
                            আবেদন তথ্য পূরণ করুন
                        </h2>
                        <p class="text-gray-600">
                            নিচের ফর্মটি সঠিকভাবে পূরণ করুন
                        </p>
                    </div>
                    
                    <div id="dynamicFieldsContainer" class="mb-8">
                        <!-- Dynamic fields will be inserted here by JavaScript -->
                    </div>
                    
                    <div class="nav-buttons">
                        <button type="button" id="prev3" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i>
                            <span>পূর্ববর্তী</span>
                        </button>
                        <button type="button" id="next3" class="btn-modern btn-primary-modern">
                            <span>পরবর্তী ধাপ</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 4: Submit -->
                <div class="wizard-step" id="step4">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            আবেদন নিশ্চিতকরণ
                        </h2>
                        <p class="text-gray-600">
                            আপনার আবেদনটি পর্যালোচনা করুন এবং জমা দিন
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-xl p-6 mb-8 border border-blue-100">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            গুরুত্বপূর্ণ তথ্য
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <span class="text-gray-700">আবেদন জমা দেওয়ার পর আপনার মোবাইল নম্বরে কনফার্মেশন এসএমএস পাঠানো হবে</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <span class="text-gray-700">আবেদনের স্ট্যাটাস 'আমার আবেদন' সেকশন থেকে ট্র্যাক করতে পারবেন</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <span class="text-gray-700">যেকোন সমস্যায় হেল্পলাইনে কল করুন: ১৬৩৪৫</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="agreement-checkbox mb-8">
                        <label class="checkbox-container">
                            <input type="checkbox" id="finalConfirmation" name="final_confirmation" value="1">
                            <span class="checkmark"></span>
                            <span class="text-gray-800 font-medium">
                                আমি নিশ্চিত করছি যে প্রদত্ত সকল তথ্য সঠিক, নির্ভুল এবং সম্পূর্ণ
                            </span>
                        </label>
                    </div>
                    
                    <div class="nav-buttons">
                        <button type="button" id="prev4" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i>
                            <span>পূর্ববর্তী</span>
                        </button>
                        <button type="submit" id="submitApplication" class="btn-modern btn-primary-modern">
                            <i class="fas fa-paper-plane"></i>
                            <span>আবেদন জমা দিন</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Help Section -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center gap-4 px-6 py-4 bg-white rounded-xl shadow-sm border border-blue-200">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-100 to-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-headset text-blue-600"></i>
                </div>
                <div class="text-left">
                    <div class="font-semibold text-gray-900">সাহায্য প্রয়োজন?</div>
                    <div class="text-gray-600">আমাদের হেল্পলাইনে কল করুন</div>
                </div>
                <a href="tel:16345" class="btn-modern btn-primary-modern">
                    <i class="fas fa-phone"></i>
                    <span>১৬৩৪৫</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    let selectedCertificateId = {{ $certificate->id }};
    let selectedCertificateFields = @json($selectedFormFields);
    
    // Step indicators and progress line
    const stepIndicators = document.querySelectorAll('.progress-step');
    const progressLine = document.getElementById('progressLine');
    
    function updateProgressLine() {
        const progress = ((currentStep - 1) / 3) * 100;
        progressLine.style.width = `${progress}%`;
    }
    
    function updateStepIndicators() {
        stepIndicators.forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index + 1 < currentStep) {
                step.classList.add('completed');
            } else if (index + 1 === currentStep) {
                step.classList.add('active');
            }
        });
        updateProgressLine();
    }
    
    // Step navigation
    function goToStep(step) {
        document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
        document.getElementById(`step${step}`).classList.add('active');
        currentStep = step;
        updateStepIndicators();
    }
    
    // Step 1: Rules agreement
    const agreeRules = document.getElementById('agreeRules');
    const next1 = document.getElementById('next1');
    
    agreeRules.addEventListener('change', function() {
        next1.disabled = !this.checked;
    });
    
    next1.addEventListener('click', function() {
        if (agreeRules.checked) {
            goToStep(2);
        }
    });
    
    // Step 2: Certificate selection
    const certificateCards = document.querySelectorAll('.certificate-card');
    const next2 = document.getElementById('next2');
    
    certificateCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selection from all cards
            document.querySelectorAll('.certificate-card-modern').forEach(c => c.classList.remove('selected'));
            // Add selection to clicked card
            this.classList.add('selected');
            
            // Update selected certificate
            selectedCertificateId = this.dataset.certificateId;
            selectedCertificateFields = JSON.parse(this.dataset.fields || '[]');
            document.getElementById('selectedCertificateId').value = selectedCertificateId;
            
            // Generate dynamic fields
            generateDynamicFields();
        });
    });
    
    // Generate dynamic fields on page load for selected certificate
    function generateDynamicFields() {
        const container = document.getElementById('dynamicFieldsContainer');
        container.innerHTML = '';
        
        if (selectedCertificateFields.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-xl">
                    <i class="fas fa-check-circle text-green-500 text-3xl mb-3"></i>
                    <p class="text-gray-600">এই সার্টিফিকেটের জন্য অতিরিক্ত তথ্যের প্রয়োজন নেই</p>
                </div>
            `;
            return;
        }
        
        // Add section header
        const header = document.createElement('div');
        header.className = 'mb-6';
        header.innerHTML = `
            <h3 class="text-xl font-bold text-gray-900 mb-3">
                <i class="fas fa-list-check text-blue-600 mr-2"></i>
                সার্টিফিকেটের তথ্য
            </h3>
            <p class="text-gray-600">
                নিচের তথ্যগুলো সঠিকভাবে পূরণ করুন। মোট ${selectedCertificateFields.length}টি ফিল্ড পূরণ করতে হবে।
            </p>
        `;
        container.appendChild(header);
        
        // Generate form fields
        selectedCertificateFields.forEach(field => {
            const fieldName = field.name || '';
            const fieldLabel = field.label || fieldName;
            const fieldType = field.type || 'text';
            const isRequired = field.required || false;
            const placeholder = field.placeholder || '';
            
            const group = document.createElement('div');
            group.className = 'form-group-modern';
            
            let fieldHtml = `
                <label class="form-label-modern ${isRequired ? 'required' : ''}">
                    ${fieldLabel}
                </label>
            `;
            
            if (fieldType === 'file') {
                fieldHtml += `
                    <input type="file" 
                           name="${fieldName}" 
                           class="form-input-modern"
                           ${isRequired ? 'required' : ''}
                           accept=".jpg,.jpeg,.png,.pdf">
                `;
            } else {
                fieldHtml += `
                    <input type="${fieldType}" 
                           name="${fieldName}" 
                           class="form-input-modern"
                           placeholder="${placeholder}"
                           ${isRequired ? 'required' : ''}>
                `;
            }
            
            group.innerHTML = fieldHtml;
            container.appendChild(group);
        });
    }
    
    // Generate fields on page load
    generateDynamicFields();
    
    // Step 2 navigation buttons
    document.getElementById('prev2').addEventListener('click', function() {
        goToStep(1);
    });
    
    next2.addEventListener('click', function() {
        goToStep(3);
    });
    
    // Step 3 navigation buttons
    document.getElementById('prev3').addEventListener('click', function() {
        goToStep(2);
    });
    
    document.getElementById('next3').addEventListener('click', function() {
        // Validate required fields
        const requiredFields = document.querySelectorAll('#step3 [required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim() && field.type !== 'file') {
                isValid = false;
                field.style.borderColor = '#ef4444';
            } else {
                field.style.borderColor = '';
            }
        });
        
        if (isValid) {
            goToStep(4);
        } else {
            alert('অনুগ্রহ করে সকল আবশ্যক তথ্য পূরণ করুন');
        }
    });
    
    // Step 4 navigation and submission
    document.getElementById('prev4').addEventListener('click', function() {
        goToStep(3);
    });
    
    // Form submission
    document.getElementById('wizardForm').addEventListener('submit', function(e) {
        // Show loading
        const submitApplication = document.getElementById('submitApplication');
        submitApplication.disabled = true;
        const originalText = submitApplication.innerHTML;
        submitApplication.innerHTML = '<span>জমা হচ্ছে...</span> <i class="fas fa-spinner fa-spin"></i>';
        
        // Allow form to submit normally
        setTimeout(() => {
            submitApplication.disabled = false;
            submitApplication.innerHTML = originalText;
        }, 5000);
    });
    
    // Initialize
    updateStepIndicators();
});
</script>
@endpush