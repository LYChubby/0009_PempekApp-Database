<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        return response()->json(Menu::all());
    }

    public function store(StoreMenuRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
        // Hapus 'menu/' yang duplikat
        $data['gambar'] = $request->file('gambar')->store('menu', 'public');
    }


        $menu = Menu::create($data);

        return response()->json($menu, 201);
    }

    public function show(Menu $menu)
    {
        return response()->json($menu);
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        $menu->update($data);

        return response()->json($menu);
    }

    public function destroy(Menu $menu)
    {
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return response()->json(['message' => 'Menu deleted.']);
    }
}

