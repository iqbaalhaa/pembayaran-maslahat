<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $table = 'tarifs';
    
    protected $fillable = [
        'nama_tarif',
        'nominal',
        'keterangan',
    ];
}
