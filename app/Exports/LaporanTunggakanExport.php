<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanTunggakanExport implements FromCollection, WithHeadings
{
    protected Collection $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'NIS' => $item->santri->nis ?? '',
                'Nama Santri' => $item->santri->nama ?? '',
                'Kelas' => optional($item->santri->kelas)->nama_kelas,
                'Tingkatan' => optional($item->santri->kelas)->tingkatan,
                'Jenis Tagihan' => $item->tarif->nama_tarif ?? '',
                'Bulan' => $item->bulan,
                'Tahun' => $item->tahun,
                'Nominal' => $item->jumlah,
                'Status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama Santri',
            'Kelas',
            'Tingkatan',
            'Jenis Tagihan',
            'Bulan',
            'Tahun',
            'Nominal',
            'Status',
        ];
    }
}
