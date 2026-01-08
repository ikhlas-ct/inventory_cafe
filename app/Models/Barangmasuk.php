<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barangmasuk extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'barang_masuks';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id_user','nomor_transaksi', 'tanggal_masuk', 'catatan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
    public function barangmasukdetail()
    {
        return $this->hasMany(BarangMasukDetail::class, 'id_barang_masuk');
    }



}
