<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Http\Request;
use App\Http\Requests\BarangRequest;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $satuans = Satuan::all();
        $kategoris = Kategori::all();
        $search = $request->query('search');
        $query = Barang::with(['kategori', 'satuan']);

        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
        }

        $barangs = $query->paginate(10); // Paginate with 10 items per page

        return view('pages.barangs.index', compact('barangs', 'search', 'kategoris', 'satuans'));
    }

    public function store(BarangRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('barang_fotos', 'public');
        }

        Barang::create($validated);

        return redirect()->route('barangs.index')->with('success', 'Barang created successfully.');
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        return view('pages.barangs.edit', compact('barang', 'kategoris', 'satuans'));
    }

    public function update(BarangRequest $request, Barang $barang)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            if ($barang->foto) {
                Storage::disk('public')->delete($barang->foto);
            }
            $validated['foto'] = $request->file('foto')->store('barang_fotos', 'public');
        }

        $barang->update($validated);

        return redirect()->route('barangs.index')->with('success', 'Barang updated successfully.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->foto) {
            Storage::disk('public')->delete($barang->foto);
        }
        $barang->delete();
        return redirect()->route('barangs.index')->with('success', 'Barang deleted successfully.');
    }
}
