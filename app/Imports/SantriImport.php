<?php

namespace App\Imports;

use App\Models\Santri;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SantriImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Santri([
            'nis'         => $row['nis'],
            'nama'        => $row['nama'],
            'kelas'       => $row['kelas'],
            'wali_santri' => $row['wali_santri'],
            'no_hp_wali'  => $row['no_hp_wali'],
            'status'      => $row['status'] ?? 'aktif',
        ]);
    }
}
