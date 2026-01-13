<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Manajer;
use App\Models\Karyawan;
use App\Models\Supplier;
use App\Models\Barangmasuk;
use Illuminate\Http\Request;
use App\Models\BarangMasukDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BarangMasukRequest;
use Illuminate\Validation\ValidationException;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $start = $request->query('start');
        $end = $request->query('end');

        $query = Barangmasuk::with(['user.karyawan', 'user.manajer'])
            ->withCount(['barangmasukdetail as jenis_count' => function ($q) {
                $q->select(DB::raw('count(distinct id_barang)'));
            }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('barangmasukdetail.barang', function ($qq) use ($search) {
                    $qq->where('nama', 'like', "%{$search}%");
                })->orWhereHas('barangmasukdetail.barang.supplier', function ($qq) use ($search) {
                    $qq->where('nama', 'like', "%{$search}%");
                })->orWhereHas('user', function ($qq) use ($search) {
                    $qq->whereHas('karyawan', function ($qqq) use ($search) {
                        $qqq->where('nama', 'like', "%{$search}%");
                    })->orWhereHas('manajer', function ($qqq) use ($search) {
                        $qqq->where('nama', 'like', "%{$search}%");
                    });
                })->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        if ($start && $end) {
            $query->whereBetween('tanggal_masuk', [$start, $end]);
        }

        $barangmasuks = $query->paginate(10);

        return view('pages.barangmasuks.index', compact('barangmasuks', 'search'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('pages.barangmasuks.create', compact('barangs'));
    }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_masuk' => 'required|date',
            'catatan' => 'nullable|string|max:1000',
            'details' => 'required|array|min:1',
            'details.*.id_barang' => 'required|exists:barangs,id',
            'details.*.jumlah' => 'required|integer|min:1',
            'details.*.tanggal_kadaluarsa' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $today = now()->format('Ymd');
            // Gunakan withTrashed() untuk count termasuk soft-deleted records
            $count = Barangmasuk::withTrashed()
                ->whereDate('created_at', now()->toDateString())
                ->count() + 1;
            $nomor_transaksi = 'BM-' . $today . '-' . sprintf('%03d', $count);

            $barangMasuk = Barangmasuk::create([
                'id_user' => Auth::id(),
                'nomor_transaksi' => $nomor_transaksi,
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $details = collect($validated['details']);

            $grouped = $details->groupBy(function ($item) {
                return $item['id_barang'] . '|' . ($item['tanggal_kadaluarsa'] ?? 'null');
            });

            foreach ($grouped as $key => $group) {
                $sum = $group->sum('jumlah');
                $first = $group->first();

                BarangMasukDetail::create([
                    'id_barang_masuk' => $barangMasuk->id,
                    'id_barang' => $first['id_barang'],
                    'jumlah' => $sum,
                    'jumlah_tersisa' => $sum,
                    'tanggal_kadaluarsa' => $first['tanggal_kadaluarsa'] ?? null,
                ]);
            }
        });

        return redirect()->route('barangmasuks.index')->with('success', 'Barang masuk berhasil ditambahkan.');
    }

    public function edit(Barangmasuk $barangmasuk)
    {
        $barangs = Barang::all();
        $barangmasuk->load('barangmasukdetail');
        return view('pages.barangmasuks.edit', compact('barangmasuk', 'barangs'));
    }
    public function update(Request $request, Barangmasuk $barangmasuk)
    {
        $validated = $request->validate([
            'tanggal_masuk' => 'required|date',
            'catatan' => 'nullable|string|max:1000',
            'details' => 'required|array|min:1',
            'details.*.id' => 'sometimes|exists:barang_masuk_details,id',
            'details.*.id_barang' => 'required|exists:barangs,id',
            'details.*.jumlah' => 'required|integer|min:1',
            'details.*.tanggal_kadaluarsa' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated, $request, $barangmasuk) {
            $barangmasuk->update([
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $submittedDetails = $validated['details'] ?? [];
            $submittedIds = [];
            $existingDetails = $barangmasuk->barangmasukdetail->keyBy('id');

            // Process existing details (updates)
            foreach ($submittedDetails as $detailData) {
                if (isset($detailData['id'])) {
                    $id = $detailData['id'];
                    $submittedIds[] = $id;
                    $detail = $existingDetails->get($id);
                    if (!$detail) continue;

                    $oldJumlah = $detail->jumlah;
                    $newJumlah = $detailData['jumlah'];
                    $delta = $newJumlah - $oldJumlah;

                    if ($delta < 0 && $detail->jumlah_tersisa + $delta < 0) {
                        throw ValidationException::withMessages([
                            'details' => "Jumlah untuk barang {$detail->barang->nama} tidak boleh dikurangi melebihi yang tersisa.",
                        ]);
                    }

                    $detail->update([
                        'id_barang' => $detailData['id_barang'],
                        'jumlah' => $newJumlah,
                        'tanggal_kadaluarsa' => $detailData['tanggal_kadaluarsa'] ?? null,
                    ]);

                    $detail->jumlah_tersisa += $delta;
                    $detail->save();
                }
            }

            // Delete removed details
            $detailsToDelete = $barangmasuk->barangmasukdetail()->whereNotIn('id', $submittedIds)->get();
            foreach ($detailsToDelete as $detail) {
                if ($detail->jumlah_tersisa < $detail->jumlah) {
                    throw ValidationException::withMessages([
                        'details' => "Tidak dapat menghapus detail barang {$detail->barang->nama} karena sudah ada yang keluar.",
                    ]);
                }
                $detail->delete();
            }

            // Process new details with merging
            $newDetails = collect($submittedDetails)->filter(fn($item) => !isset($item['id']));
            if ($newDetails->isNotEmpty()) {
                $groupedNew = $newDetails->groupBy(function ($item) {
                    return $item['id_barang'] . '|' . ($item['tanggal_kadaluarsa'] ?? 'null');
                });

                // Reload current details after updates and deletes
                $barangmasuk->load('barangmasukdetail');
                $currentMap = [];
                foreach ($barangmasuk->barangmasukdetail as $det) {
                    $tanggalKey = $det->tanggal_kadaluarsa ? $det->tanggal_kadaluarsa->format('Y-m-d') : 'null';
                    $key = $det->id_barang . '|' . $tanggalKey;
                    $currentMap[$key] = $det;
                }

                foreach ($groupedNew as $key => $group) {
                    $sum = $group->sum('jumlah');
                    $first = $group->first();
                    $tanggal = $first['tanggal_kadaluarsa'] ?? null;

                    if (isset($currentMap[$key])) {
                        $det = $currentMap[$key];
                        $det->jumlah += $sum;
                        $det->jumlah_tersisa += $sum;
                        $det->save();
                    } else {
                        BarangMasukDetail::create([
                            'id_barang_masuk' => $barangmasuk->id,
                            'id_barang' => $first['id_barang'],
                            'jumlah' => $sum,
                            'jumlah_tersisa' => $sum,
                            'tanggal_kadaluarsa' => $tanggal,
                        ]);
                    }
                }
            }

            // After all operations, check for duplicates and merge if any
            $barangmasuk->load('barangmasukdetail');
            $detailsAfter = $barangmasuk->barangmasukdetail;
            $groupedAfter = $detailsAfter->groupBy(function ($det) {
                $tanggalKey = $det->tanggal_kadaluarsa ? $det->tanggal_kadaluarsa->format('Y-m-d') : 'null';
                return $det->id_barang . '|' . $tanggalKey;
            });

            foreach ($groupedAfter as $key => $group) {
                if ($group->count() > 1) {
                    $totalJumlah = $group->sum('jumlah');
                    $totalTersisa = $group->sum('jumlah_tersisa');
                    $first = $group->first();

                    // Update first one
                    $first->jumlah = $totalJumlah;
                    $first->jumlah_tersisa = $totalTersisa;
                    $first->save();

                    // Delete the rest
                    $group->slice(1)->each->delete();
                }
            }
        });

        return redirect()->route('barangmasuks.index')->with('success', 'Barang masuk berhasil diupdate.');
    }



    public function destroy(Barangmasuk $barangmasuk)
    {
        DB::transaction(function () use ($barangmasuk) {
            $barangmasuk->load('barangmasukdetail');

            foreach ($barangmasuk->barangmasukdetail as $detail) {
                if ($detail->jumlah_tersisa < $detail->jumlah) {
                    throw ValidationException::withMessages([
                        'details' => "Tidak dapat menghapus detail barang {$detail->barang->nama} karena sudah ada yang keluar.",
                    ]);
                }
            }

            // If no issues, delete details and the main record
            $barangmasuk->barangmasukdetail()->delete();
            $barangmasuk->delete();
        });

        return redirect()->route('barangmasuks.index')->with('success', 'Barang Masuk berhasil dihapus.');
    }

    public function show(Barangmasuk $barangmasuk)
    {
        $barangmasuk->load('barangmasukdetail.barang');
        return view('pages.barangmasuks.detail', compact('barangmasuk'));
    }


    public function laporanBarangMasuk(Request $request)
    {
        $start_date = Carbon::parse($request->query('start', now()->startOfMonth()))->startOfDay();
        $end_date = Carbon::parse($request->query('end', now()->endOfMonth()))->endOfDay();

        $barang_masuks = Barangmasuk::with(['barangmasukdetail.barang.kategori', 'barangmasukdetail.barang.satuan', 'barangmasukdetail.barang.supplier'])
            ->whereBetween('tanggal_masuk', [$start_date, $end_date])
            ->get();

        $details = collect();
        foreach ($barang_masuks as $bm) {
            $details = $details->merge($bm->barangmasukdetail);
        }

        $total_jumlah = $details->sum('jumlah');
        $total_harga = $details->sum(function ($detail) {
            return $detail->jumlah * $detail->barang->harga;
        });

        // Asumsi manajer diambil dari user yang authenticated, atau ambil manajer pertama jika ada multiple
        $manajer = auth()->user()->manajer ?? Manajer::first();

        return view('pages.barangmasuks.cetak', compact('details', 'total_jumlah', 'total_harga', 'manajer', 'start_date', 'end_date'));
    }
}
