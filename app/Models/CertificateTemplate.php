<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'certificate_type_id',
        'title',
        'body',
        'is_active'
    ];

    public function certificateType()
    {
        return $this->belongsTo(CertificateType::class);
    }
}
