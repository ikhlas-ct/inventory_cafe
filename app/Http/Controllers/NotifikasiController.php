<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('pages.notifikasi.index', compact('notifications'));
    }

    public function read($id)
    {
        DatabaseNotification::where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->update(['read_at' => now()]);

        return back();
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
