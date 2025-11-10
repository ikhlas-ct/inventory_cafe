<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangmasuk extends Model
{
    protected $table = 'barang_masuks';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id_barang', 'jumlah', 'tanggal_masuk', 'catatan', 'harga_beli', 'id_karyawan', 'id_supplier'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'harga_beli' => 'decimal:2'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang','id');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan','id');
    }

      public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier','id');
    }

}
