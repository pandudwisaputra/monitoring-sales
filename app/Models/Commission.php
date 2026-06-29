<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'periode',
        'total_penjualan',
        'persentase_komisi',
        'total_pembayaran',
        'status'
    ];

    // 🔗 belongsTo user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 hasMany payment
    public function payments()
    {
        return $this->hasMany(
            CommissionPayment::class
        );
    }
}