<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Pembayaran::with('transaksi');

        if ($user->role !== 'admin') {
            $query->whereHas('transaksi', fn($q) => $q->where('user_id', $user->id));
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $transaksi = Transaksi::where('id', $request->transaksi_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

        $pembayaran = Pembayaran::create([
            'transaksi_id' => $transaksi->id,
            'bukti_bayar' => $path,
            'status' => 'pending',
        ]);

        return response()->json($pembayaran, 201);
    }

    public function show(Pembayaran $pembayaran)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $pembayaran->transaksi->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($pembayaran->load('transaksi'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'status' => 'required|in:pending,verifikasi',
            'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->only('status');

        if ($request->hasFile('bukti_bayar')) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
            $data['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        $pembayaran->update($data);

        return response()->json($pembayaran);
    }

    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->bukti_bayar) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $pembayaran->delete();

        return response()->json(['message' => 'Pembayaran dihapus']);
    }
}
