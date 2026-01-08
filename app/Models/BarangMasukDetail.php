<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasukDetail extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'barang_masuk_details';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id_barang_masuk',
        'id_barang',
        'jumlah',
        'jumlah_tersisa',
        "tanggal_kadaluarsa"

    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }
    public function barangMasuk()
    {
        return $this->belongsTo(Barangmasuk::class, 'id_barang_masuk', 'id');
    }
}
