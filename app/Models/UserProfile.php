<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name_bn',
        'name_en',
        'father_name_bn',
        'father_name_en',
        'mother_name_bn',
        'mother_name_en',
        'dob',
        'gender',
        'marital_status',
        'religion',
        'height',
        'birth_mark',
        'present_address',
        'permanent_address',
        'village',
        'ward',
        'occupation',
        'education',
        'quota',
        'nid_number',
        'profile_photo',
        'is_complete',
    ];

    // Add this
    protected $casts = [
        'dob' => 'date', // now dob will be Carbon instance
    ];
}
