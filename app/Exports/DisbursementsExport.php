<?php

namespace App\Exports;

use App\Models\CommissionPayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DisbursementsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        return CommissionPayment::with(['commission.user'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID Pembayaran',
            'Nama Sales',
            'Jumlah',
            'Status',
            'Tanggal Bayar',
            'Flip ID',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->commission->user->nama ?? '-',
            $payment->jumlah,
            ucfirst($payment->disbursement_status),
            $payment->tanggal_bayar ? date('d M Y', strtotime($payment->tanggal_bayar)) : '-',
            $payment->flip_disbursement_id ?? '-',
        ];
    }
}
