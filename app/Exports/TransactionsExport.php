<?php

namespace App\Exports;

use App\Models\SalesTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $userId;
    protected $customerId;
    protected $tanggal;

    public function __construct($userId, $customerId, $tanggal)
    {
        $this->userId = $userId;
        $this->customerId = $customerId;
        $this->tanggal = $tanggal;
    }

    public function query()
    {
        $query = SalesTransaction::with(['customer', 'user', 'details.product']);

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        if ($this->customerId) {
            $query->where('customer_id', $this->customerId);
        }

        if ($this->tanggal) {
            $query->whereDate('tanggal_transaksi', $this->tanggal);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Nama Sales',
            'Nama Customer',
            'Total Harga',
            'Tanggal Transaksi',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->user->nama ?? '-',
            $transaction->customer->nama_customer ?? '-',
            $transaction->total_harga,
            $transaction->tanggal_transaksi ? date('d M Y', strtotime($transaction->tanggal_transaksi)) : '-',
        ];
    }
}
