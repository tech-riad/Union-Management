@extends('layouts.app')
@section('title','Applications')

@section('content')
<h1 class="text-2xl font-bold mb-6">Applications</h1>

@if(auth()->user()->role === 'citizen')
<a href="{{ route('applications.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700">New Application</a>
@endif

<table class="min-w-full bg-white shadow rounded overflow-hidden">
    <thead class="bg-gray-200">
        <tr>
            <th class="py-3 px-4 text-left">Certificate</th>
            <th class="py-3 px-4 text-left">Status</th>
            <th class="py-3 px-4 text-left">Submitted At</th>
            @if(auth()->user()->role === 'secretary' || auth()->user()->role === 'admin')
            <th class="py-3 px-4 text-left">Actions</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($applications as $app)
        <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-4">{{ $app->certificateType->name }}</td>
            <td class="py-2 px-4">{{ $app->status }}</td>
            <td class="py-2 px-4">{{ $app->created_at->format('d M, Y') }}</td>
            @if(auth()->user()->role === 'secretary' || auth()->user()->role === 'admin')
            <td class="py-2 px-4 flex space-x-2">
                @if($app->status === 'Pending')
                <form action="{{ route('applications.approve', $app->id) }}" method="POST">
                    @csrf
                    <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Approve</button>
                </form>
                <form action="{{ route('applications.reject', $app->id) }}" method="POST">
                    @csrf
                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Reject</button>
                </form>
                @else
                <span class="text-gray-500 italic">No Actions</span>
                @endif
            </td>
            @endif
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center py-4 text-gray-500">No applications found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
