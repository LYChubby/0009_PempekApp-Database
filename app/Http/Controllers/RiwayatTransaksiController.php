<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;

class RiwayatTransaksiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Transaksi::with(['pemesanans.menu', 'pembayaran', 'user'])
            ->orderBy('created_at', 'desc');

        // Customer hanya melihat pesanan miliknya
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $riwayat = $query->get()->map(function ($transaksi) {
            return [
                'id_transaksi' => $transaksi->id,
                'nama_user' => $transaksi->user->name ?? '-',
                'pengiriman' => $transaksi->pengiriman,
                'metode_pembayaran' => $transaksi->metode_pembayaran,
                'status_bayar' => $transaksi->status_bayar,
                'status_pembayaran' => $transaksi->pembayaran->status ?? 'Belum Upload',
                'status_pesanan' => optional($transaksi->pemesanans->first())->status_pesanan ?? '-',
                'bukti_pembayaran' => $transaksi->pembayaran?->bukti_bayar ? asset('storage/' . $transaksi->pembayaran->bukti_bayar) : null,
                'tanggal_transaksi' => $transaksi->created_at->toDateTimeString(),
                'tanggal_bayar' => optional($transaksi->pembayaran?->created_at)->toDateTimeString(),

                'items' => $transaksi->pemesanans->map(function ($item) {
                    return [
                        'menu' => $item->menu->nama ?? '-',
                        'jumlah' => $item->jumlah,
                        'harga_satuan' => $item->harga_satuan,
                        'total_harga' => $item->total_harga,
                    ];
                }),
            ];
        });

        return response()->json($riwayat);
    }
}
