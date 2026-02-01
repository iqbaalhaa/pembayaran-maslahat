<?php

namespace App\Imports;

use App\Models\Santri;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AkunSantriImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Create User first
        $user = User::create([
            'name'     => $row['nama'],
            'username' => $row['nis'],
            'email'    => $row['nis'] . '@santri.com', // Dummy email
            'password' => Hash::make($row['nis']), // Default password is NIS
            'role'     => 'santri',
        ]);

        // Find Kelas ID if exists
        $kelasData = null;
        if (!empty($row['kelas'])) {
            $kelasData = Kelas::where('nama_kelas', $row['kelas'])->first();
        }

        // Create Santri linked to User
        return new Santri([
            'user_id'     => $user->id,
            'nis'         => $row['nis'],
            'nama'        => $row['nama'],
            'kelas'       => $row['kelas'] ?? null,
            'kelas_id'    => $kelasData ? $kelasData->id : null,
            'wali_santri' => $row['wali_santri'] ?? null,
            'no_hp_wali'  => $row['no_hp_wali'] ?? null,
            'status'      => 'aktif',
        ]);
    }

    public function rules(): array
    {
        return [
            'nis' => 'required|unique:santri,nis|unique:users,username',
            'nama' => 'required',
        ];
    }
}
