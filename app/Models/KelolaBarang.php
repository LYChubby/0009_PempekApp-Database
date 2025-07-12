<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelolaBarang extends Model
{
    protected $table = 'kelolabarangs';
    protected $fillable = [
        'nama_barang',
        'jumlah',
        'tanggal_masuk',
    ];
}
