<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    /**
     * Tampilkan semua pemesanan.
     * - Admin: melihat semua pesanan
     * - Customer: hanya melihat pesanan miliknya (berdasarkan transaksi.user_id)
     */
    public function index()
    {
        $user = Auth::user();

        $query = Pemesanan::with(['menu', 'transaksi']);

        if ($user->role !== 'admin') {
            $query->whereHas('transaksi', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return response()->json($query->latest()->get());
    }

    /**
     * Tampilkan detail satu pemesanan.
     * - Admin: bebas
     * - Customer: hanya jika pemesanan miliknya
     */
    public function show(Pemesanan $pemesanan)
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            if (!$pemesanan->transaksi || $pemesanan->transaksi->user_id !== $user->id) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
        }

        return response()->json($pemesanan->load(['menu', 'transaksi']));
    }

    /**
     * Nonaktifkan create pemesanan karena sudah dilakukan via Transaksi.
     */
    public function store()
    {
        return response()->json([
            'message' => 'Pemesanan dilakukan melalui proses checkout transaksi.'
        ], 403);
    }

    /**
     * Update pemesanan (opsional: hanya untuk admin).
     */
    public function update(Request $request, Pemesanan $pemesanan)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Akses hanya untuk admin.'], 403);
        }

        $validated = $request->validate([
            'jumlah' => 'sometimes|integer|min:1',
            'harga_satuan' => 'sometimes|integer|min:0',
            'total_harga' => 'sometimes|integer|min:0',
            'status_pesanan' => 'sometimes|string|in:diterima,diproses,dikirim,selesai',
        ]);

        $pemesanan->update($validated);

        return response()->json($pemesanan->load('menu'));
    }

    /**
     * Hapus pemesanan (opsional: hanya untuk admin).
     */
    public function destroy(Pemesanan $pemesanan)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Akses hanya untuk admin.'], 403);
        }

        $pemesanan->delete();

        return response()->json(['message' => 'Pemesanan berhasil dihapus.']);
    }
}
