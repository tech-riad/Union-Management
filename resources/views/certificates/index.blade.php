@extends('layouts.app')
@section('title','Certificates')
@section('content')
<h1 class="text-2xl font-bold mb-4">Certificates</h1>
@if(auth()->user()->role === 'super_admin')
<a href="{{ route('certificate.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Add Certificate</a>
@endif

<table class="min-w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="py-2 px-4">Name</th>
            <th class="py-2 px-4">Fee</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($certificates as $cert)
        <tr class="border-b">
            <td class="py-2 px-4">{{ $cert->name }}</td>
            <td class="py-2 px-4">{{ $cert->fee }}</td>
            <td class="py-2 px-4 flex space-x-2">
                @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('certificate.edit',$cert->id) }}" class="bg-yellow-400 text-white px-2 py-1 rounded">Edit</a>
                <form action="{{ route('certificate.destroy',$cert->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="bg-red-600 text-white px-2 py-1 rounded">Delete</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
