<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class AkunSantriTemplateExport implements WithHeadings, FromCollection
{
    public function headings(): array
    {
        return [
            'nama',
            'nis',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([
            [
                'nama' => 'Contoh Nama Santri',
                'nis' => '1234567890',
            ]
        ]);
    }
}
