@php
use App\Helpers\UnionHelper;
@endphp
@extends('layouts.app')

@section('title', 'আমার ইনভয়েস সমূহ')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- হেডার সেকশন -->
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                        <span class="text-blue-600">আমার</span> ইনভয়েস সমূহ
                    </h1>
                    <p class="text-gray-600 text-lg">
                        আপনার সমস্ত চালানের তালিকা ও ব্যবস্থাপনা
                    </p>
                </div>
                
                <!-- স্ট্যাটাস কার্ড -->
                <div class="mt-4 md:mt-0 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-gray-800">{{ $invoices->count() }}</div>
                        <div class="text-sm text-gray-600">মোট ইনভয়েস</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $invoices->where('payment_status', 'paid')->count() }}
                        </div>
                        <div class="text-sm text-gray-600">পরিশোধিত</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-red-600">
                            {{ $invoices->where('payment_status', 'unpaid')->count() }}
                        </div>
                        <div class="text-sm text-gray-600">বকেয়া</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">
                            ৳ {{ number_format($invoices->sum('amount'), 2) }}
                        </div>
                        <div class="text-sm text-gray-600">মোট টাকা</div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        @if($invoices->isEmpty())
            <!-- খালি স্টেট -->
            <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="text-gray-400 mb-6">
                        <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-3">কোন ইনভয়েস নেই</h3>
                    <p class="text-gray-500 mb-8">
                        আপনার এখন পর্যন্ত কোন চালান তৈরি হয়নি। নতুন সেবা নেওয়ার পর এখানে চালান দেখা যাবে।
                    </p>
                    <a href="{{ route('citizen.applications.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        নতুন আবেদন করুন
                    </a>
                </div>
            </div>
        @else
            <!-- ইনভয়েস টেবিল -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- টেবিল হেডার -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">ইনভয়েস তালিকা</h2>
                            <p class="text-sm text-gray-600 mt-1">আপনার সমস্ত চালানের বিস্তারিত তথ্য</p>
                        </div>
                        
                        <!-- একশনে -->
                        <div class="mt-3 md:mt-0 flex space-x-2">
                            <button onclick="printTable()" 
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                প্রিন্ট
                            </button>
                        </div>
                    </div>
                </div>

                <!-- টেবিল কন্টেন্ট -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="invoicesTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    চালান নং
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    সেবার ধরন
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    তারিখ
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    পরিমাণ
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    অবস্থা
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    কর্ম
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoices as $invoice)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <!-- চালান নং -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $invoice->invoice_no }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                আবেদন: {{ $invoice->application->application_no ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- সেবার ধরন -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 font-medium">
                                        {{ optional($invoice->application->certificateType)->name ?? 'সনদ সেবা' }}
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        @if($invoice->application)
                                            {{ optional($invoice->application)->created_at->format('d/m/Y') }}
                                        @endif
                                    </div>
                                </td>

                                <!-- তারিখ -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $invoice->created_at->format('d M, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $invoice->created_at->format('h:i A') }}
                                    </div>
                                </td>

                                <!-- পরিমাণ -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold text-gray-900">
                                        ৳ {{ number_format($invoice->amount, 2) }}
                                    </div>
                                    @if($invoice->vat_amount > 0 || $invoice->service_charge > 0)
                                    <div class="text-xs text-gray-500 mt-1">
                                        + ভ্যাট: ৳{{ number_format($invoice->vat_amount ?? 0, 2) }}
                                        @if($invoice->service_charge > 0)
                                        <br>+ সার্ভিস: ৳{{ number_format($invoice->service_charge ?? 0, 2) }}
                                        @endif
                                    </div>
                                    @endif
                                </td>

                                <!-- অবস্থা -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($invoice->payment_status == 'paid')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        পরিশোধিত
                                    </span>
                                    @if($invoice->paid_at)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $invoice->paid_at->format('d/m/Y') }}
                                    </div>
                                    @endif
                                    @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        বকেয়া
                                    </span>
                                    @endif
                                </td>

                                <!-- কর্ম -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- PDF ডাউনলোড -->
                                        <a href="{{ route('citizen.invoices.pdf', $invoice) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300"
                                           title="PDF ডাউনলোড">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            PDF
                                        </a>

                                        <!-- পেমেন্ট বাটন -->
                                        @if($invoice->payment_status == 'unpaid')
                                        <a href="{{ route('citizen.payments.show', $invoice) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300"
                                           title="bKash-এ পেমেন্ট করুন">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Pay Now
                                        </a>
                                        @endif

                                        <!-- বিস্তারিত দেখুন -->
                                        <a href="{{ route('citizen.invoices.show', $invoice) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300"
                                           title="বিস্তারিত দেখুন">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- টেবিল ফুটার -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="text-sm text-gray-500 mb-2 md:mb-0">
                            মোট <span class="font-semibold">{{ $invoices->count() }}</span> টি ইনভয়েস
                        </div>
                    </div>
                </div>
            </div>

            <!-- পেমেন্ট সহায়তা কার্ড -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- পেমেন্ট তথ্য -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold">দ্রুত পেমেন্ট করুন</h3>
                    </div>
                    <div class="space-y-3">
                        <p class="text-sm opacity-90">
                            বকেয়া ইনভয়েসের জন্য "Pay Now" বাটনে ক্লিক করে bKash-এ দ্রুত পেমেন্ট করুন।
                        </p>
                        <div class="mt-4">
                            <a href="https://www.bkash.com/" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-white text-green-600 font-semibold rounded-lg hover:bg-gray-100 transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                bKash ওয়েবসাইট
                            </a>
                        </div>
                    </div>
                </div>

                <!-- সহায়তা কার্ড -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">সহায়তা</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Pay Now বাটনে ক্লিক করে bKash-এ পেমেন্ট করুন
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            পেমেন্ট সম্পন্ন হলে স্বয়ংক্রিয়ভাবে PDF ডাউনলোড হবে
                        </li>
                        <li class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            সমস্যা হলে  {{ UnionHelper::getContactNumber() }} নম্বরে কল করুন
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// টেবিল প্রিন্ট ফাংশন
function printTable() {
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>ইনভয়েস তালিকা</title>');
    printWindow.document.write('<style>');
    printWindow.document.write(`
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .paid { color: green; }
        .unpaid { color: red; }
    `);
    printWindow.document.write('</style></head><body>');
    
    const table = document.getElementById('invoicesTable').cloneNode(true);
    const buttons = table.querySelectorAll('td:last-child');
    buttons.forEach(button => button.remove());
    
    printWindow.document.write('<h2>আমার ইনভয়েস সমূহ</h2>');
    printWindow.document.write(table.outerHTML);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.print();
}

// স্বয়ংক্রিয়ভাবে সফল মেসেজ হাইড করুন
document.addEventListener('DOMContentLoaded', function() {
    // সফল/ত্রুটি মেসেজ ৫ সেকেন্ড পর হাইড করুন
    setTimeout(() => {
        const alerts = document.querySelectorAll('[class*="bg-"]');
        alerts.forEach(alert => {
            if (alert.classList.contains('bg-green-100') || alert.classList.contains('bg-red-100')) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
    
    // Pay Now বাটনে ক্লিক করলে কনফার্মেশন
    const payButtons = document.querySelectorAll('a[href*="payments"]');
    payButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('আপনি কি bKash-এ পেমেন্ট করতে চান?')) {
                e.preventDefault();
            }
        });
    });
});
</script>

<style>
@media print {
    nav, footer, button, a, .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
        font-size: 12pt !important;
    }
    
    .bg-gradient-to-b, .shadow-lg, .shadow {
        box-shadow: none !important;
        background: white !important;
    }
    
    table {
        font-size: 10pt !important;
    }
    
    .invoice-row {
        border-bottom: 1px solid #ddd !important;
    }
}
</style>
@endpush
@endsection