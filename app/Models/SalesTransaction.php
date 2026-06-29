<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'tanggal_transaksi',
        'total_harga'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'total_harga' => 'decimal:2',
    ];

    // 🔗 belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 belongsTo Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 🔗 hasMany SalesDetail
    public function details()
    {
        return $this->hasMany(
            SalesDetail::class,
            'transaction_id'
        );
    }
}