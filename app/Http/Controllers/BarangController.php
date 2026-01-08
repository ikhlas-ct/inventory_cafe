<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Kategori;
use App\Models\Supplier;
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

        $query = Barang::with(['kategori', 'satuan'])
            ->withSum(['barangMasuksDetail as stok' => function ($q) {
                $q->where('jumlah_tersisa', '>', 0);
            }], 'jumlah_tersisa');

        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                ->orWhere('deskripsi', 'LIKE', "%{$search}%");
        }

        $barangs = $query->paginate(10);

        return view('pages.barangs.index', compact('barangs', 'search', 'kategoris', 'satuans'));
    }


    public function create()
    {
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        $suppliers = Supplier::all();
        return view('pages.barangs.create', compact('kategoris', 'satuans', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_supplier' => 'required|exists:suppliers,id',
            'barangs' => 'required|array|min:1',
            'barangs.*.kode_barang' => 'required|unique:barangs,kode_barang|max:255',
            'barangs.*.nama' => 'required|max:255',
            'barangs.*.id_kategori' => 'required|exists:kategoris,id',
            'barangs.*.id_satuan' => 'required|exists:satuans,id',
            'barangs.*.harga' => 'required|numeric|min:0',
            'barangs.*.deskripsi' => 'nullable|max:1000',
            'barangs.*.foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $id_supplier = $validated['id_supplier'];
        $barangsData = $validated['barangs'];
        $barangFiles = $request->file('barangs');

        foreach ($barangsData as $index => $barangData) {
            // Tambahkan id_supplier ke data barang
            $barangData['id_supplier'] = $id_supplier;

            // Handle file upload untuk setiap barang
            if (isset($barangFiles[$index]['foto']) && $barangFiles[$index]['foto']->isValid()) {
                $path = $barangFiles[$index]['foto']->store('barang_fotos', 'public');
                $barangData['foto'] = $path;
            }

            Barang::create($barangData);
        }

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan.');
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
