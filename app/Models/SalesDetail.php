<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'jumlah',
        'harga',
        'subtotal'
    ];

    // 🔗 belongsTo transaction
    public function transaction()
    {
        return $this->belongsTo(
            SalesTransaction::class,
            'transaction_id'
        );
    }

    // 🔗 belongsTo product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}