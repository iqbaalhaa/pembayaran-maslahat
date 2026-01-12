<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
        'tarif_id',
        'bulan',
        'tahun',
        'jumlah',
        'status',
        'keterangan',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class);
    }
}
