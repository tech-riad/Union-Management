<!-- Secretay Dashboard -->
@extends('layouts.dashboard') <!-- যদি shared layout থাকে -->
@section('content')
<h1 class="text-2xl font-bold mb-4">Secretary Dashboard</h1>

<div class="bg-white shadow rounded p-4">
    <h2 class="font-bold mb-2">Pending Applications</h2>
    <table class="min-w-full table-auto">
        <thead>
            <tr class="bg-gray-200">
                <th>ID</th>
                <th>Citizen</th>
                <th>Certificate</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach(\App\Models\Application::where('status','Pending')->latest()->get() as $app)
            <tr class="text-center border-b">
                <td>{{ $app->id }}</td>
                <td>{{ $app->user->name ?? 'N/A' }}</td>
                <td>{{ $app->certificateType->name ?? 'N/A' }}</td>
                <td>
                    <form action="{{ route('applications.approve',$app->id) }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-green-500 text-white px-2 py-1 rounded">Approve</button>
                    </form>
                    <form action="{{ route('applications.reject',$app->id) }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-red-500 text-white px-2 py-1 rounded">Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
