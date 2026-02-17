<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class KaryawanLoginNotification extends Notification
{
    use Queueable;

    protected $karyawan;

    public function __construct($karyawan)
    {
        $this->karyawan = $karyawan;
    }

    public function via($notifiable)
    {
        return ['database']; // simpan ke database
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->karyawan->nama . ' baru saja login.',
            'karyawan_id' => $this->karyawan->id,
            'login_time' => now(),
        ];
    }
}
