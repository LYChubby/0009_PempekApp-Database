<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Http\Requests\TransaksiRequest;

class TransaksiController extends Controller
{
    public function store(TransaksiRequest $request)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'user_id' => $request->user_id,
                'pengiriman' => $request->pengiriman,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_bayar' => 'belum',
            ]);

            foreach ($request->items as $item) {
                Pemesanan::create([
                    'transaksi_id' => $transaksi->id,
                    'user_id' => $request->user_id,
                    'menu_id' => $item['menu_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total_harga' => $item['total_harga'],
                ]);
            }

            // Upload bukti bayar dan simpan
            if ($request->hasFile('bukti_bayar')) {
                $bukti = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
                Pembayaran::create([
                    'transaksi_id' => $transaksi->id,
                    'bukti_bayar' => $bukti,
                    'status' => 'pending',
                ]);
            }

            DB::commit();
            return response()->json([
    'message' => 'Checkout berhasil',
    'data' => ['id' => $transaksi->id]
], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Checkout gagal', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_bayar' => 'required|in:sudah,belum',
            'status' => 'required|in:pending,terverifikasi',
        ]);

        $transaksi = Transaksi::with('pembayaran')->findOrFail($id);
        $transaksi->update([
            'status_bayar' => $request->status_bayar,
        ]);

        if ($request->has('status') && $transaksi->pembayaran) {
        $transaksi->pembayaran->update([
            'status' => $request->status,
        ]);
    }

        return response()->json([
            'message' => 'Status bayar transaksi berhasil diperbarui.',
            'data' => $transaksi,
        ]);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::with(['pemesanans', 'pembayaran'])->findOrFail($id);

        // Hapus relasi terlebih dahulu jika ada
        $transaksi->pemesanans()->delete();
        $transaksi->pembayaran()?->delete();

        $transaksi->delete();

        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }

}
