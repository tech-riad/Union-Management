@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Complete Your Profile</h2>
    @if(session('error'))
        <div class="bg-red-100 p-2 mb-4 text-red-700">{{ session('error') }}</div>
    @endif
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label>Profile Photo</label>
            <input type="file" name="profile_photo">
        </div>
        <div>
            <label>নাম (Bangla)</label>
            <input type="text" name="name_bangla" value="{{ old('name_bangla', $profile->name_bangla) }}">
        </div>
        <div>
            <label>Name (English)</label>
            <input type="text" name="name_english" value="{{ old('name_english', $profile->name_english) }}">
        </div>
        <div>
            <label>পিতার নাম (Bangla)</label>
            <input type="text" name="father_name_bangla" value="{{ old('father_name_bangla', $profile->father_name_bangla) }}">
        </div>
        <div>
            <label>Father's Name (English)</label>
            <input type="text" name="father_name_english" value="{{ old('father_name_english', $profile->father_name_english) }}">
        </div>
        <div>
            <label>মাতার নাম (Bangla)</label>
            <input type="text" name="mother_name_bangla" value="{{ old('mother_name_bangla', $profile->mother_name_bangla) }}">
        </div>
        <div>
            <label>Mother's Name (English)</label>
            <input type="text" name="mother_name_english" value="{{ old('mother_name_english', $profile->mother_name_english) }}">
        </div>
        <div>
            <label>জন্ম তারিখ</label>
            <input type="date" name="dob" value="{{ old('dob', $profile->dob) }}">
        </div>
        <div>
            <label>NID</label>
            <input type="text" name="nid" value="{{ old('nid', $profile->nid) }}">
        </div>
        <div>
            <label>Present Address</label>
            <input type="text" name="present_address" value="{{ old('present_address', $profile->present_address) }}">
        </div>
        <div>
            <label>Permanent Address</label>
            <input type="text" name="permanent_address" value="{{ old('permanent_address', $profile->permanent_address) }}">
        </div>
        <div>
            <label>Birth Place</label>
            <input type="text" name="birth_place" value="{{ old('birth_place', $profile->birth_place) }}">
        </div>
        <div>
            <label>Religion</label>
            <input type="text" name="religion" value="{{ old('religion', $profile->religion) }}">
        </div>
        <div>
            <label>Height</label>
            <input type="text" name="height" value="{{ old('height', $profile->height) }}">
        </div>
        <div>
            <label>Birth Mark</label>
            <input type="text" name="birth_mark" value="{{ old('birth_mark', $profile->birth_mark) }}">
        </div>
        <div>
            <label>Gender</label>
            <select name="gender">
                <option value="Male" {{ $profile->gender=='Male'?'selected':'' }}>Male</option>
                <option value="Female" {{ $profile->gender=='Female'?'selected':'' }}>Female</option>
            </select>
        </div>
        <div>
            <label>Marital Status</label>
            <input type="text" name="marital_status" value="{{ old('marital_status', $profile->marital_status) }}">
        </div>
        <div>
            <label>Quota</label>
            <input type="text" name="quota" value="{{ old('quota', $profile->quota) }}">
        </div>
        <div>
            <label>Profession</label>
            <input type="text" name="profession" value="{{ old('profession', $profile->profession) }}">
        </div>
        <div>
            <label>Education</label>
            <input type="text" name="education" value="{{ old('education', $profile->education) }}">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Profile</button>
    </form>
</div>
@endsection
