@extends('layouts.app')
@section('title','Users')
@section('content')
<h1 class="text-2xl font-bold mb-4">Users</h1>
<a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Add User</a>

<table class="min-w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="py-2 px-4">Name</th>
            <th class="py-2 px-4">Email</th>
            <th class="py-2 px-4">Role</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr class="border-b">
            <td class="py-2 px-4">{{ $user->name }}</td>
            <td class="py-2 px-4">{{ $user->email }}</td>
            <td class="py-2 px-4">{{ $user->role }}</td>
            <td class="py-2 px-4 flex space-x-2">
                <a href="{{ route('users.edit',$user->id) }}" class="bg-yellow-400 text-white px-2 py-1 rounded">Edit</a>
                <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="bg-red-600 text-white px-2 py-1 rounded">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
