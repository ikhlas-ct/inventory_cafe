<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangkeluar extends Model
{
    protected $table = 'barang_keluars';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id_barang', 'jumlah', 'tanggal_keluar', 'catatan', 'id_karyawan', 'id_supplier'
    ];

    protected $casts = [
        'tanggal_keluar' => 'date'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang','id');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan','id');
    }


}
