<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        return User::where('role', 'sales')->orderBy('nama')->get();
    }

    public function headings(): array
    {
        return [
            'ID Sales',
            'Nama',
            'Email',
            'Bank',
            'Nama Rekening',
            'Nomor Rekening',
            'Tanggal Bergabung',
        ];
    }

    public function map($sales): array
    {
        return [
            $sales->id,
            $sales->nama,
            $sales->email,
            $sales->bank ?? '-',
            $sales->nama_rekening ?? '-',
            $sales->nomor_rekening ?? '-',
            $sales->created_at ? $sales->created_at->format('d M Y') : '-',
        ];
    }
}
