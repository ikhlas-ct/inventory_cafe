<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BarangKeluarNotification extends Notification
{
    use Queueable;

    protected $barangKeluar;
    protected $type; // store atau update

    public function __construct($barangKeluar, $type)
    {
        $this->barangKeluar = $barangKeluar;
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
                ? 'Barang Keluar baru ditambahkan'
                : 'Barang Keluar telah diupdate',

            'nomor_transaksi' => $this->barangKeluar->nomor_transaksi,
            'tanggal_keluar' => $this->barangKeluar->tanggal_keluar,
            'id_barang_keluar' => $this->barangKeluar->id,
            'created_by' => $this->barangKeluar->user->name ?? 'User',

            // Tambahkan URL/route detail
            'url' => route('barangkeluars.show', $this->barangKeluar->id),
        ];
    }
}
