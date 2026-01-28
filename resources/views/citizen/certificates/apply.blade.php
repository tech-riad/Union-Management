@extends('layouts.app')

@section('title', 'Apply Certificate')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <div class="bg-white shadow-lg rounded-2xl p-8 border">

        <h1 class="text-xl font-bold text-gray-800 mb-4">
            📄 {{ $certificate->name }} আবেদন
        </h1>

        <p class="text-gray-600 mb-6">
            আপনার প্রোফাইল তথ্য ব্যবহার করে এই সার্টিফিকেটের আবেদন জমা হবে।
        </p>

        <div class="bg-gray-50 border rounded-xl p-4 mb-6">
            <p><strong>নাম:</strong> {{ auth()->user()->profile->name_bn }}</p>
            <p><strong>পিতার নাম:</strong> {{ auth()->user()->profile->father_name_bn }}</p>
            <p><strong>মাতার নাম:</strong> {{ auth()->user()->profile->mother_name_bn }}</p>
            <p><strong>ঠিকানা:</strong> {{ auth()->user()->profile->present_address }}</p>
        </div>

        <form method="POST" action="{{ route('citizen.certificates.store', $certificate) }}">
            @csrf

            <div class="flex justify-between">
                <a href="{{ route('citizen.certificates.index') }}"
                   class="px-4 py-2 rounded-xl border">
                    ⬅ ফিরে যান
                </a>

                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl">
                    আবেদন নিশ্চিত করুন
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
