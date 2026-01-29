<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BarangKedaluwarsaTerdekatNotification extends Notification
{
    use Queueable;

    public function __construct(
        public $barangId,
        public $namaBarang,
        public $sisaHari,
        public $tanggalKadaluarsa
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'barang_id' => $this->barangId,
            'nama_barang' => $this->namaBarang,
            'tanggal_kadaluarsa' => $this->tanggalKadaluarsa->format('Y-m-d'),
            'sisa_hari' => $this->sisaHari,
            'pesan' => "Barang {$this->namaBarang} akan kedaluwarsa dalam {$this->sisaHari} ."
        ];
    }
}
