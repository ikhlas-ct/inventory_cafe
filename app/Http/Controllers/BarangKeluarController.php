<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Barangkeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BarangKeluarRequest;

class BarangKeluarController extends Controller
{
  public function index(Request $request)
    {
        $search = $request->query('search');
        $barangs = Barang::all();
        $query = Barangkeluar::with(['barang', 'karyawan']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })->orWhereHas('supplier', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })->orWhereHas('karyawan', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        $barangkeluars = $query->paginate(10);

        return view('pages.barangkeluars.index', compact('barangkeluars', 'search', 'barangs', ));
    }

      public function store(BarangKeluarRequest $request)
    {
        $validated = $request->validated();

        $validated['id_karyawan'] = Auth::user()->karyawan->id;

        $barang = Barang::find($validated['id_barang']);

        $currentstock = $barang->stok;
        $newJumlah = $validated['jumlah'];

        if ($newJumlah > $currentstock) {
            return redirect()->back()->withErrors(['jumlah' => 'Stok barang tidak mencukupi untuk keluar sebanyak ' . $newJumlah . '. Stok saat ini: ' . $currentstock])->withInput();
        }


        Barangkeluar::create($validated);
        $barang->decrement('stok', $validated['jumlah']);


        return redirect()->route('barangkeluars.index')->with('success', 'Barang Keluar berhasil dibuat.');
    }

    public function edit(Barangkeluar $barangkeluar)
    {
        $barangs = Barang::all();
        return view('pages.barangkeluars.edit', compact('barangkeluar', 'barangs'));
    }

    public function update(BarangKeluarRequest $request, Barangkeluar $barangkeluar)
    {
        $validated = $request->validated();
        $barang = Barang::find($validated['id_barang']);

        $currentstock = $barang->stok ;
        $newjumlah = $validated['jumlah'];
        $oldjumlah = $barangkeluar->jumlah;

        $fixstock = ($currentstock + $oldjumlah) - $newjumlah;

        if ($fixstock < 0) {
            return redirect()->back()->withErrors(['jumlah' => 'Stok barang tidak mencukupi untuk keluar sebanyak ' . $newjumlah . '. Stok saat ini: ' . $currentstock])->withInput();
        }

        $barangkeluar->update($validated);
        $barangkeluar->barang->decrement('stok', $validated['jumlah']);

        $barang = $barangkeluar->barang; // Reload jika perlu, tapi biasanya OK
        $barang->stok = $fixstock;
        $barang->save();

        return redirect()->route('barangkeluars.index')->with('success', 'Barang Keluar berhasil diperbarui.');
    }

    public function destroy(Barangkeluar $barangkeluar)
    {
        // Kembalikan stok barang sebelum menghapus record
        $barang = $barangkeluar->barang;
        $barang->increment('stok', $barangkeluar->jumlah);

        $barangkeluar->delete();

        return redirect()->route('barangkeluars.index')->with('success', 'Barang Keluar berhasil dihapus.');
    }


}
