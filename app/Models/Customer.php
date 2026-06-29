<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_customer',
        'no_hp',
        'alamat'
    ];

    public function salesTransactions()
    {
        return $this->hasMany(SalesTransaction::class);
    }
}