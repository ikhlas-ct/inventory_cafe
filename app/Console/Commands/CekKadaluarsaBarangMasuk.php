<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\User;
use App\Notifications\BarangKedaluwarsaTerdekatNotification;
use Carbon\Carbon;

class CekKadaluarsaBarangMasuk extends Command
{
    protected $signature = 'barang:cek-kadaluarsa-terdekat';
    protected $description = 'Cek tanggal kadaluarsa terdekat per barang dan kirim notifikasi';

    public function handle()
    {
        $batasHari = 7;
        // 1) ambil per barang: tanggal kadaluarsa TERDEKAT dimana masih ada stok (jumlah_tersisa > 0)
        $rows = DB::table('barang_masuk_details')
            ->select('id_barang', DB::raw('MIN(tanggal_kadaluarsa) as kadaluarsa_terdekat'))
            ->where('jumlah_tersisa', '>', 0)
            ->whereNotNull('tanggal_kadaluarsa')
            ->whereDate('tanggal_kadaluarsa', '<=', now()->addDays($batasHari))
            ->groupBy('id_barang')
            ->get();

        if ($rows->isEmpty()) {
            $this->info('Tidak ada barang yang akan kedaluwarsa dalam ' . $batasHari . ' hari.');
            return 0;
        }

        // load barang data dalam batch
        $barangIds = $rows->pluck('id_barang')->unique()->values()->all();
        $barangs = Barang::whereIn('id', $barangIds)->get()->keyBy('id');

        // pengguna yang akan dikirimi notifikasi (sesuaikan: admins / semua user)
        $users = User::where('role', 'admin')->get(); // ubah sesuai kebutuhan
        if ($users->isEmpty()) {
            $users = User::all();
        }

        foreach ($rows as $row) {
            $idBarang = $row->id_barang;
            if (!isset($barangs[$idBarang])) continue; // safety

            $tanggalKadaluarsa = Carbon::parse($row->kadaluarsa_terdekat);
            $sisaHari = now()->diffInDays($tanggalKadaluarsa, false);
            if ($sisaHari < 0) continue; // sudah lewat

            foreach ($users as $user) {
                // Cegah duplikat: per user, per barang, per hari
                $exists = $user->notifications()
                    ->where('data->barang_id', $idBarang)
                    ->whereDate('created_at', now())
                    ->exists();

                if ($exists) continue;

                $user->notify(new BarangKedaluwarsaTerdekatNotification(
                    $idBarang,
                    $barangs[$idBarang]->nama,
                    $sisaHari,
                    $tanggalKadaluarsa
                ));
            }
        }

        $this->info('Proses cek dan kirim notifikasi selesai.');
        return 0;
    }
}
