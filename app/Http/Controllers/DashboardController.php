<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Barang;
use App\Models\BarangMasukDetail;
use App\Models\BarangKeluarDetail;
use App\Models\Barangmasuk;
use App\Models\Barangkeluar;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Karyawan;

class DashboardController extends Controller
{
    public function index()
    {
        // Nama user (asumsi dari auth, sesuaikan jika perlu)
        $name = auth()->user()->name;

        // Total Barang (jumlah jenis barang)
        $totalBarang = Barang::count();

        // Total Supplier
        $totalSupplier = Supplier::count();

        // Total Kategori
        $totalKategori = Kategori::count();

        // Karyawan Aktif (asumsi semua karyawan aktif)
        $karyawanAktif = Karyawan::count();

        // Transaksi Bulan Ini (masuk + keluar)
        $transaksiBulanIni = Barangmasuk::whereMonth('tanggal_masuk', now()->month)
            ->whereYear('tanggal_masuk', now()->year)
            ->count() + Barangkeluar::whereMonth('tanggal_keluar', now()->month)
            ->whereYear('tanggal_keluar', now()->year)
            ->count();

        // Barang Masuk Bulan Ini
        $barangMasukThisMonth = BarangMasukDetail::join('barang_masuks', 'barang_masuk_details.id_barang_masuk', '=', 'barang_masuks.id')
            ->whereMonth('barang_masuks.tanggal_masuk', now()->month)
            ->whereYear('barang_masuks.tanggal_masuk', now()->year)
            ->sum('jumlah');

        // Barang Keluar Bulan Ini
        $barangKeluarThisMonth = BarangKeluarDetail::join('barang_keluars', 'barang_keluar_details.id_barang_keluar', '=', 'barang_keluars.id')
            ->whereMonth('barang_keluars.tanggal_keluar', now()->month)
            ->whereYear('barang_keluars.tanggal_keluar', now()->year)
            ->sum('jumlah');

        // Hitung persentase untuk Barang Keluar (dibandingkan bulan lalu)
        $lastMonth = now()->subMonth();
        $keluarLastMonth = BarangKeluarDetail::join('barang_keluars', 'barang_keluar_details.id_barang_keluar', '=', 'barang_keluars.id')
            ->whereMonth('barang_keluars.tanggal_keluar', $lastMonth->month)
            ->whereYear('barang_keluars.tanggal_keluar', $lastMonth->year)
            ->sum('jumlah');
        $percentKeluar = $keluarLastMonth > 0 ? (($barangKeluarThisMonth - $keluarLastMonth) / $keluarLastMonth) * 100 : 0;
        $percentKeluarText = number_format($percentKeluar, 1) . '% ' . ($percentKeluar > 0 ? 'peningkatan dari bulan lalu' : 'penurunan dari bulan lalu');

        // Persentase untuk Total Barang (misal persentase stok keseluruhan, atau kosongkan jika tidak relevan)
        $percentBarangText = ''; // Sesuaikan jika ada logika spesifik, misal persentase barang baru

        // Stok Kritis (barang dengan stok < 10)
        $allBarangs = Barang::with('kategori')->get();
        $criticalBarangs = $allBarangs->filter(function ($barang) {
            return $barang->stok_sekarang < 10;
        });
        $stokKritisCount = $criticalBarangs->count();

        // Aktivitas Terbaru
        $masuks = Barangmasuk::orderBy('tanggal_masuk', 'desc')->take(5)->get()->map(function ($m) {
            return (object) [
                'type' => 'masuk',
                'nomor_transaksi' => $m->nomor_transaksi,
                'tanggal' => $m->tanggal_masuk,
            ];
        });
        $keluars = Barangkeluar::orderBy('tanggal_keluar', 'desc')->take(5)->get()->map(function ($k) {
            return (object) [
                'type' => 'keluar',
                'nomor_transaksi' => $k->nomor_transaksi,
                'tanggal' => $k->tanggal_keluar,
            ];
        });
        $activities = $masuks->merge($keluars)->sortByDesc('tanggal')->take(10);

        // Data untuk Chart Perkembangan Stok Bulanan (6 bulan terakhir)
        $months = [];
        $masukData = [];
        $keluarData = [];
        $end = now()->endOfMonth();
        $start = now()->subMonths(5)->startOfMonth();

        // Ambil data masuk bulanan
        $masukMonthly = BarangMasukDetail::join('barang_masuks', 'barang_masuk_details.id_barang_masuk', '=', 'barang_masuks.id')
            ->select(DB::raw('SUM(jumlah) as total'), DB::raw('MONTH(tanggal_masuk) as month'), DB::raw('YEAR(tanggal_masuk) as year'))
            ->whereBetween('tanggal_masuk', [$start, $end])
            ->groupBy('year', 'month')
            ->get()->keyBy(function ($item) {
                return $item->year . '-' . $item->month;
            });

        // Ambil data keluar bulanan
        $keluarMonthly = BarangKeluarDetail::join('barang_keluars', 'barang_keluar_details.id_barang_keluar', '=', 'barang_keluars.id')
            ->select(DB::raw('SUM(jumlah) as total'), DB::raw('MONTH(tanggal_keluar) as month'), DB::raw('YEAR(tanggal_keluar) as year'))
            ->whereBetween('tanggal_keluar', [$start, $end])
            ->groupBy('year', 'month')
            ->get()->keyBy(function ($item) {
                return $item->year . '-' . $item->month;
            });

        // Loop untuk setiap bulan
        for ($date = $start->copy(); $date->lte($end); $date->addMonth()) {
            $months[] = $date->format('M Y');
            $key = $date->year . '-' . $date->month;
            $masukData[] = isset($masukMonthly[$key]) ? $masukMonthly[$key]->total : 0;
            $keluarData[] = isset($keluarMonthly[$key]) ? $keluarMonthly[$key]->total : 0;
        }

        // Data untuk Chart Kategori (doughnut)
        $kategories = Kategori::withCount('barangs')->get();
        $catLabels = $kategories->pluck('nama')->toArray();
        $catData = $kategories->pluck('barangs_count')->toArray();

        return view('pages.dashboard.index', compact(
            'name',
            'totalBarang',
            'percentBarangText',
            'barangMasukThisMonth',
            'barangKeluarThisMonth',
            'percentKeluarText',
            'stokKritisCount',
            'totalSupplier',
            'totalKategori',
            'karyawanAktif',
            'transaksiBulanIni',
            'criticalBarangs',
            'activities',
            'months',
            'masukData',
            'keluarData',
            'catLabels',
            'catData'
        ));
    }
}
