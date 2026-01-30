<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class AamarpayTransaction extends Model
{
    use HasFactory;

    protected $table = 'aamarpay_transactions';

    protected $fillable = [
        'invoice_id',
        'tran_id',
        'amount',
        'currency',
        'status',
        'request_payload',
        'response_payload',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
