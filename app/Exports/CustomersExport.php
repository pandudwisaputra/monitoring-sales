<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        return Customer::orderBy('nama_customer')->get();
    }

    public function headings(): array
    {
        return [
            'ID Customer',
            'Nama Customer',
            'Email',
            'Telepon',
            'Alamat',
            'Tanggal Terdaftar',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->nama_customer,
            $customer->email ?? '-',
            $customer->telepon ?? '-',
            $customer->alamat ?? '-',
            $customer->created_at ? $customer->created_at->format('d M Y') : '-',
        ];
    }
}
