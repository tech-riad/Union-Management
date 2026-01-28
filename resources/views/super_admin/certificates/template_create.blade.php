@extends('layouts.app')

@section('title','Certificate Template')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow rounded">

    <h2 class="text-xl font-bold mb-4">
        {{ $certificate->name }} - Certificate Template
    </h2>

    <form method="POST"
          action="{{ route('super_admin.certificates.template.store',$certificate) }}">
        @csrf

        <div class="mb-4">
            <label class="font-semibold">Template Title</label>
            <input type="text" name="title"
                   class="w-full border p-2 rounded"
                   required>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Template Body (HTML)</label>

<textarea name="body" rows="12"
class="w-full border p-3 font-mono text-sm"
placeholder="
<h1>সনদপত্র</h1>
<p>এই মর্মে প্রত্যয়ন করা যাচ্ছে যে {{name}}</p>
<p>পিতা/মাতা: {{father_name}}</p>
">
</textarea>

            <p class="text-sm text-gray-500 mt-2">
                Available placeholders:
                <code>{{name}}</code>,
                <code>{{father_name}}</code>,
                <code>{{nid}}</code>,
                <code>{{address}}</code>,
                <code>{{date}}</code>
            </p>
        </div>

        <button class="px-5 py-2 bg-blue-600 text-white rounded">
            Save Template
        </button>

    </form>
</div>
@endsection
