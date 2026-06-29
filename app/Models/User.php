<?php
namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'nama_rekening',
        'nomor_rekening',
        'bank'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🔗 RELATIONSHIP

    public function salesTransactions()
    {
        return $this->hasMany(SalesTransaction::class);
    }

    public function targets()
    {
        return $this->hasMany(Target::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }
}