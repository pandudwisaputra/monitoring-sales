<?php

namespace App\Exports;

use App\Models\Target;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TargetsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        return Target::with('user')->orderBy('periode', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID Target',
            'Nama Sales',
            'Periode',
            'Target Nominal',
            'Target Kuantitas',
            'Komisi (%)',
            'Dibuat Pada',
        ];
    }

    public function map($target): array
    {
        return [
            $target->id,
            $target->user->nama ?? '-',
            $target->periode,
            $target->target_nominal,
            $target->target_kuantitas,
            $target->persentase_komisi,
            $target->created_at ? $target->created_at->format('d M Y') : '-',
        ];
    }
}
