<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SantriExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Santri::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'NIS',
            'Nama',
            'Kelas',
            'Wali Santri',
            'No HP Wali',
            'Status',
            'Created At',
            'Updated At',
        ];
    }
}
