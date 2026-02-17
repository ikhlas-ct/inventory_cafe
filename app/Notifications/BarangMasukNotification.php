<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BarangMasukNotification extends Notification
{
    use Queueable;

    protected $barangMasuk;
    protected $type; // store atau update

    public function __construct($barangMasuk, $type)
    {
        $this->barangMasuk = $barangMasuk;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->type === 'store'
                ? 'Barang Masuk baru ditambahkan'
                : 'Barang Masuk telah diupdate',

            'nomor_transaksi' => $this->barangMasuk->nomor_transaksi,
            'tanggal_masuk' => $this->barangMasuk->tanggal_masuk,
            'id_barang_masuk' => $this->barangMasuk->id,
            'created_by' => $this->barangMasuk->user->karyawan->nama ?? 'User',

            // Tambahkan URL/route detail
            'url' => route('barangmasuks.show', $this->barangMasuk->id),
        ];
    }
}
