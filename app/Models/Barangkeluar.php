<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barangkeluar extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'barang_keluars';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id_karyawan', 'nomor_transaksi', 'tanggal_keluar', 'catatan',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date'
    ];


    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan','id');
    }
    public function barangkeluardetail()
    {
        return $this->hasMany(BarangKeluarDetail::class, 'id_barang_keluar', 'id');
    }


}
