<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'user_id',
        'menu_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'status_pesanan',
        'pengiriman',
        'metode_pembayaran',
        'status_bayar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}

