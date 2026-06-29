<?php

namespace App\Exports;

use App\Models\Commission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class CommissionExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'ID',
            'User ID',
            'Nama Sales',
            'Periode',
            'Total Penjualan',
            'Persentase Komisi (%)',
            'Total Pembayaran',
            'Status',
            'Tanggal Dibuat',
            'Tanggal Diupdate',
        ];
    }

    public function collection()
    {
        return Commission::with('user')
            ->get()
            ->map(function ($commission) {
                return [
                    $commission->id,
                    $commission->user_id,
                    $commission->user->nama ?? '-',
                    $commission->periode,
                    $commission->total_penjualan,
                    $commission->persentase_komisi,
                    $commission->total_pembayaran,
                    ucfirst($commission->status),
                    $commission->created_at->format('Y-m-d H:i:s'),
                    $commission->updated_at->format('Y-m-d H:i:s'),
                ];
            });
    }
}
