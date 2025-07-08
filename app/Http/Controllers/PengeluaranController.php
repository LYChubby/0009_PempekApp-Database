<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Http\Requests\StorePengeluaranRequest;
use App\Http\Requests\UpdatePengeluaranRequest;

class PengeluaranController extends Controller
{
    public function index()
    {
        return response()->json(
            Pengeluaran::orderBy('tanggal', 'desc')->get()
        );
    }

    public function store(StorePengeluaranRequest $request)
    {
        $pengeluaran = Pengeluaran::create($request->validated());

        return response()->json([
            'message' => 'Pengeluaran berhasil ditambahkan.',
            'data' => $pengeluaran
        ], 201);
    }

    public function show(Pengeluaran $pengeluaran)
    {
        return response()->json($pengeluaran);
    }

    public function update(UpdatePengeluaranRequest $request, Pengeluaran $pengeluaran)
    {
        $pengeluaran->update($request->validated());

        return response()->json([
            'message' => 'Pengeluaran berhasil diperbarui.',
            'data' => $pengeluaran
        ]);
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return response()->json([
            'message' => 'Pengeluaran berhasil dihapus.'
        ]);
    }
}
