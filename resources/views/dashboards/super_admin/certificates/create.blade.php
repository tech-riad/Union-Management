@extends('layouts.app')
@section('content')
<h3>Create Certificate Type</h3>
<form action="{{ route('super-admin.certificates.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<label>Name</label>
<input type="text" name="name" class="form-control">

<label>Fee</label>
<input type="number" name="fee" class="form-control">

<label>PDF Template (Optional)</label>
<input type="file" name="template_path" class="form-control">

<h4>Form Fields</h4>
<div id="fields">
    <div class="field-row">
        <input type="text" name="form_fields[0][name]" placeholder="Field Name">
        <select name="form_fields[0][type]">
            <option value="text">Text</option>
            <option value="file">File</option>
        </select>
        <select name="form_fields[0][required]">
            <option value="1">Required</option>
            <option value="0">Optional</option>
        </select>
    </div>
</div>

<button type="submit" class="btn btn-primary mt-2">Create</button>
</form>
@endsection
