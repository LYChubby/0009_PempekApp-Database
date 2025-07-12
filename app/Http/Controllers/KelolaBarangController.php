<?php

namespace App\Http\Controllers;

use App\Models\KelolaBarang;
use App\Http\Requests\StoreKelolaBarangRequest;
use App\Http\Requests\UpdateKelolaBarangRequest;
use Illuminate\Http\Request;

class KelolaBarangController extends Controller
{
    public function index()
    {
        // Logic to retrieve and return a list of items
        return response()->json(
            kelolaBarang::orderBy('tanggal_masuk', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        // Logic to validate and store a new item
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
        ]);

        // Assuming KelolaBarang is a model that handles the items
        $item = KelolaBarang::create($validatedData);

        return response()->json($item, 201);
    }

    public function show(KelolaBarang $kelolaBarang)
    {
        return response()->json($kelolaBarang);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'sometimes|required|string|max:255',
            'jumlah' => 'sometimes|required|string|max:255',
            'tanggal_masuk' => 'sometimes|required|date',
        ]);

        $item = KelolaBarang::findOrFail($id);
        $item->update($validatedData);

        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = KelolaBarang::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully.']);
    }
}
