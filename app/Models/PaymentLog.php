<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'commission_id',
        'order_id',
        'transaction_status',
        'payload'
    ];

    public function commission()
    {
        return $this->belongsTo(
            Commission::class
        );
    }
}