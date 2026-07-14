<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommissionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_id',
        'tanggal_bayar',
        'jumlah',
        'flip_disbursement_id',
        'disbursement_status',
        'account_holder',
        'bank_code',
        'account_number',
        'recipient_name',
        'sender_bank',
        'remark',
        'receipt',
        'time_served',
        'fee',
        'beneficiary_email',
        'idempotency_key',
        'direction',
        'is_virtual_account',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah' => 'decimal:2',
        'is_virtual_account' => 'boolean',
    ];

    public function commission()
    {
        return $this->belongsTo(
            Commission::class
        );
    }

    public function getDisbursementIdAttribute(): ?string
    {
        return $this->flip_disbursement_id;
    }

    public function setDisbursementIdAttribute(?string $value): void
    {
        $this->attributes['flip_disbursement_id'] = $value;
    }

    /**
     * Scope: Paid disbursements
     */
    public function scopePaid($query)
    {
        return $query->where('disbursement_status', 'paid');
    }

    /**
     * Scope: Cancelled disbursements
     */
    public function scopeCancelled($query)
    {
        return $query->where('disbursement_status', 'cancelled');
    }

    /**
     * Scope: Pending disbursements
     */
    public function scopePending($query)
    {
        return $query->where('disbursement_status', 'pending');
    }

    /**
     * Scope: Failed disbursements
     */
    public function scopeFailed($query)
    {
        return $query->where('disbursement_status', 'failed');
    }

    /**
     * Check if payment is paid
     */
    public function isPaid(): bool
    {
        return $this->disbursement_status === 'paid';
    }

    /**
     * Check if payment is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->disbursement_status === 'cancelled';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->disbursement_status === 'pending';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed(): bool
    {
        return $this->disbursement_status === 'failed';
    }
}
