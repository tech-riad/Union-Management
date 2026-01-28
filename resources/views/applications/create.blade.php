@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Apply for Certificate</h2>

    <form action="{{ route('application.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Certificate Type</label>
            <select name="certificate_type_id" class="form-control">
                @foreach($certificates as $certificate)
                    <option value="{{ $certificate->id }}">{{ $certificate->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Form Data (JSON)</label>
            <textarea name="form_data[sample_field]" class="form-control" rows="5"></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Submit</button>
    </form>
</div>
@endsection
