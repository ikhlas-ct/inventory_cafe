<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Manajer;
use App\Models\Barangkeluar;
use Illuminate\Http\Request;
use App\Models\BarangMasukDetail;
use App\Models\BarangKeluarDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BarangKeluarController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');
        $start = $request->query('start');
        $end = $request->query('end');

        $query = Barangkeluar::with(['user.karyawan', 'user.manajer'])
            ->withCount(['barangkeluardetail as jenis_count' => function ($q) {
                $q->select(DB::raw('count(distinct id_barang)'));
            }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('barangkeluardetail.barang', function ($qq) use ($search) {
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
            $query->whereBetween('tanggal_keluar', [$start, $end]);
        }

        $barangkeluars = $query->paginate(10);

        return view('pages.barangkeluars.index', compact('barangkeluars', 'search'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('pages.barangkeluars.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_keluar' => 'required|date',
            'catatan' => 'nullable|string|max:1000',
            'details' => 'required|array|min:1',
            'details.*.id_barang' => 'required|exists:barangs,id',
            'details.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $today = now()->format('Ymd');
            $count = Barangkeluar::withTrashed()
                ->whereDate('created_at', now()->toDateString())
                ->count() + 1;
            $nomor_transaksi = 'BK-' . $today . '-' . sprintf('%03d', $count);

            $barangKeluar = Barangkeluar::create([
                'id_user' => Auth::id(),
                'nomor_transaksi' => $nomor_transaksi,
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $details = collect($validated['details'])->groupBy('id_barang');

            foreach ($details as $id_barang => $group) {
                $totalJumlah = $group->sum('jumlah');

                // Validasi sisa stok sebelum proses
                $sisaStok = BarangMasukDetail::where('id_barang', $id_barang)->sum('jumlah_tersisa');
                if ($totalJumlah > $sisaStok) {
                    throw ValidationException::withMessages([
                        'details' => "Jumlah keluar melebihi sisa stok untuk barang ID {$id_barang}. Sisa stok: {$sisaStok}",
                    ]);
                }

                $keluarDetail = BarangKeluarDetail::create([
                    'id_barang_keluar' => $barangKeluar->id,
                    'id_barang' => $id_barang,
                    'jumlah' => $totalJumlah,
                ]);

                $this->deductFromMasuk($id_barang, $totalJumlah);
            }
        });

        return redirect()->route('barangkeluars.index')->with('success', 'Barang keluar berhasil ditambahkan.');
    }

    public function show(Barangkeluar $barangkeluar)
    {
        $barangkeluar->load('barangkeluardetail.barang', 'user.karyawan');
        return view('pages.barangkeluars.detail', compact('barangkeluar'));
    }

    public function edit(Barangkeluar $barangkeluar)
    {
        $barangs = Barang::all();
        $barangkeluar->load('barangkeluardetail');
        return view('pages.barangkeluars.edit', compact('barangkeluar', 'barangs'));
    }

    public function update(Request $request, Barangkeluar $barangkeluar)
    {
        $validated = $request->validate([
            'tanggal_keluar' => 'required|date',
            'catatan' => 'nullable|string|max:1000',
            'details' => 'required|array|min:1',
            'details.*.id' => 'sometimes|exists:barang_keluar_details,id',
            'details.*.id_barang' => 'required|exists:barangs,id',
            'details.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $barangkeluar) {
            $barangkeluar->update([
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $submittedDetails = collect($validated['details']);
            $submittedIds = $submittedDetails->filter(fn($item) => isset($item['id']))->pluck('id')->toArray();
            $existingDetails = $barangkeluar->barangkeluardetail->keyBy('id');

            // Handle deletes
            $detailsToDelete = $existingDetails->except($submittedIds);
            foreach ($detailsToDelete as $detail) {
                $this->addBackToMasuk($detail->id_barang, $detail->jumlah);
                $detail->delete();
            }

            // Group submitted by id_barang for new and updates
            $groupedSubmitted = $submittedDetails->groupBy('id_barang');

            // Reload to get current state
            $barangkeluar->load('barangkeluardetail');
            $currentDetails = $barangkeluar->barangkeluardetail->keyBy('id_barang');

            foreach ($groupedSubmitted as $id_barang => $group) {
                $totalJumlah = $group->sum('jumlah');

                // Validasi sisa stok sebelum proses (akumulasi dengan yang ada jika update)
                $sisaStok = BarangMasukDetail::where('id_barang', $id_barang)->sum('jumlah_tersisa');
                $existingJumlah = $currentDetails->get($id_barang)->jumlah ?? 0;
                $netJumlah = $totalJumlah - $existingJumlah;
                if ($netJumlah > $sisaStok) {
                    throw ValidationException::withMessages([
                        'details' => "Jumlah keluar melebihi sisa stok untuk barang ID {$id_barang}. Sisa stok: {$sisaStok}",
                    ]);
                }

                $existing = $currentDetails->get($id_barang);
                if ($existing) {
                    // Update existing
                    $oldJumlah = $existing->jumlah;
                    $this->addBackToMasuk($id_barang, $oldJumlah);
                    $existing->jumlah = $totalJumlah;
                    $existing->save();
                    $this->deductFromMasuk($id_barang, $totalJumlah);
                } else {
                    // New
                    $newDetail = BarangKeluarDetail::create([
                        'id_barang_keluar' => $barangkeluar->id,
                        'id_barang' => $id_barang,
                        'jumlah' => $totalJumlah,
                    ]);
                    $this->deductFromMasuk($id_barang, $totalJumlah);
                }
            }
        });

        return redirect()->route('barangkeluars.index')->with('success', 'Barang keluar berhasil diupdate.');
    }

    public function destroy(Barangkeluar $barangkeluar)
    {
        DB::transaction(function () use ($barangkeluar) {
            foreach ($barangkeluar->barangkeluardetail as $detail) {
                $this->addBackToMasuk($detail->id_barang, $detail->jumlah);
                $detail->delete();
            }
            $barangkeluar->delete();
        });

        return redirect()->route('barangkeluars.index')->with('success', 'Barang keluar berhasil dihapus.');
    }

    private function deductFromMasuk($id_barang, $jumlah)
    {
        $remaining = $jumlah;
        $masukDetails = BarangMasukDetail::where('id_barang', $id_barang)
            ->where('jumlah_tersisa', '>', 0)
            ->orderByRaw('tanggal_kadaluarsa IS NULL DESC, tanggal_kadaluarsa DESC')
            ->get();

        foreach ($masukDetails as $masukDetail) {
            if ($remaining <= 0) break;
            $take = min($remaining, $masukDetail->jumlah_tersisa);
            $masukDetail->jumlah_tersisa -= $take;
            $masukDetail->save();
            $remaining -= $take;
        }

        if ($remaining > 0) {
            throw ValidationException::withMessages([
                'details' => "Stok tidak cukup untuk barang ID {$id_barang}.",
            ]);
        }
    }

    private function addBackToMasuk($id_barang, $jumlah)
    {
        $remaining = $jumlah;
        $masukDetails = BarangMasukDetail::where('id_barang', $id_barang)
            ->whereRaw('jumlah_tersisa < jumlah')
            ->orderByRaw('tanggal_kadaluarsa IS NULL DESC, tanggal_kadaluarsa DESC')
            ->get();

        foreach ($masukDetails as $masukDetail) {
            if ($remaining <= 0) break;
            $room = $masukDetail->jumlah - $masukDetail->jumlah_tersisa;
            $add = min($remaining, $room);
            $masukDetail->jumlah_tersisa += $add;
            $masukDetail->save();
            $remaining -= $add;
        }

        if ($remaining > 0) {
            throw ValidationException::withMessages([
                'details' => "Tidak dapat menambahkan kembali stok untuk barang ID {$id_barang}.",
            ]);
        }
    }

    public function laporanBarangKeluar(Request $request)
    {
        $start_date = Carbon::parse($request->query('start', now()->startOfMonth()))->startOfDay();
        $end_date = Carbon::parse($request->query('end', now()->endOfMonth()))->endOfDay();

        $barang_keluars = Barangkeluar::with(['barangkeluardetail.barang.satuan'])
            ->whereBetween('tanggal_keluar', [$start_date, $end_date])
            ->get();

        $details = collect();
        foreach ($barang_keluars as $bk) {
            $details = $details->merge($bk->barangkeluardetail);
        }

        $total_jumlah = $details->sum('jumlah');

        // Asumsi manajer diambil dari user yang authenticated, atau ambil manajer pertama jika ada multiple
        $manajer = auth()->user()->manajer ?? Manajer::first();

        return view('pages.barangkeluars.cetak', compact('details', 'total_jumlah', 'manajer', 'start_date', 'end_date'));
    }
}
