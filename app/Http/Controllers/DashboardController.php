<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pengeluaran;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        // Jumlah total pemesanan
        $totalPenjualan = DB::table('transaksis')->count();

        // Jumlah total pemasukan: sum total_harga dari pemesanan yang terkait transaksi status = sudah
        $totalPemasukan = DB::table('pemesanans')
            ->join('transaksis', 'pemesanans.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.status_bayar', 'sudah')
            ->sum('pemesanans.total_harga');

        // Total pengeluaran
        $totalPengeluaran = DB::table('pengeluarans')->sum('jumlah');

        // Hitung saldo akhir
        $balance = $totalPemasukan - $totalPengeluaran;

        return response()->json([
            'total_penjualan' => (int) $totalPenjualan,
            'total_pemasukan' => (int) $totalPemasukan,
            'total_pengeluaran' => (int) $totalPengeluaran,
            'balance' => (int) $balance,
        ]);
    }

}
