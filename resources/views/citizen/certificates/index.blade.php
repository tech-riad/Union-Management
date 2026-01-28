@extends('layouts.app')

@section('title', 'Certificates')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        📄 সার্টিফিকেট আবেদন
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($certificates as $certificate)
            <div class="bg-white border rounded-2xl p-6 shadow-sm hover:shadow-md transition">

                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $certificate->name }}
                </h2>

                <p class="text-gray-500 text-sm mt-2">
                    {{ $certificate->description ?? 'এই সার্টিফিকেটের জন্য আবেদন করা যাবে।' }}
                </p>

                <div class="mt-4">
                    <a href="{{ route('citizen.certificates.apply', $certificate) }}"
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm">
                        আবেদন করুন
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">কোন সার্টিফিকেট পাওয়া যায়নি</p>
        @endforelse
    </div>

</div>
@endsection
