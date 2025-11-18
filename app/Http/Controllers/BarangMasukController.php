<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Karyawan;
use App\Models\Supplier;
use App\Models\Barangmasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BarangMasukRequest;
use Illuminate\Validation\ValidationException;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $barangs = Barang::all();
        $suppliers = Supplier::all();
        $query = Barangmasuk::with(['barang', 'karyawan', 'supplier']);

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

        $barangmasuks = $query->paginate(10);

        return view('pages.barangmasuks.index', compact('barangmasuks', 'search', 'barangs', 'suppliers'));
    }

  public function store(BarangMasukRequest $request)
    {
        $validated = $request->validated();

        $validated['id_karyawan'] = Auth::user()->karyawan->id;

        $barang = Barang::find($validated['id_barang']);
        $validated['harga_beli'] = $barang->harga;

        Barangmasuk::create($validated);
        $barang->increment('stok', $validated['jumlah']);


        return redirect()->route('barangmasuks.index')->with('success', 'Barang Masuk berhasil dibuat.');
    }

public function edit(Barangmasuk $barangmasuk)
{
    $barangs = Barang::all();
    $suppliers = Supplier::all();
    return view('pages.barangmasuks.edit', compact('barangmasuk', 'barangs', 'suppliers'));
}

public function update(BarangMasukRequest $request, Barangmasuk $barangmasuk)
{
    $validated = $request->validated();

    $oldJumlah = $barangmasuk->jumlah;
    $newJumlah = $validated['jumlah'];

    // Hitung delta untuk validasi (sama seperti sebelumnya)
    $delta = $oldJumlah - $newJumlah;

    // Validasi agar stok baru tidak minus (stok - old + new < 0)
    if ($delta > 0) { // Hanya jika new < old (stok berkurang)
        $barang = $barangmasuk->barang;
        $currentStok = $barang->stok_sekarang;

        if ($currentStok - $delta < 0) {
            throw ValidationException::withMessages([
                'jumlah' => 'Update akan menyebabkan stok barang menjadi minus. Stok saat ini: ' . $currentStok,
            ]);
        }
    }

    // Update record Barangmasuk
    $barangmasuk->update($validated);

    // Adjust stok di Barang: stok += (new - old)
    $barang = $barangmasuk->barang; // Reload jika perlu, tapi biasanya OK
    $barang->stok += ($newJumlah - $oldJumlah);
    $barang->save();

    return redirect()->route('barangmasuks.index')->with('success', 'Barang Masuk berhasil diupdate.');
}

public function destroy(Barangmasuk $barangmasuk)
{
    $jumlah = $barangmasuk->jumlah;
    $barang = $barangmasuk->barang;
    $currentStok = $barang->stok_sekarang;

    // Validasi agar stok - jumlah tidak minus
    if ($currentStok - $jumlah < 0) {
        throw ValidationException::withMessages([
            'general' => 'Penghapusan akan menyebabkan stok barang menjadi minus. Stok saat ini: ' . $currentStok,
        ]);
    }

    // Adjust stok: stok -= jumlah
    $barang->stok -= $jumlah;
    $barang->save();

    // Hapus record
    $barangmasuk->delete();

    return redirect()->route('barangmasuks.index')->with('success', 'Barang Masuk berhasil dihapus.');
}
}
