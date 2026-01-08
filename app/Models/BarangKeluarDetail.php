<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluarDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'barang_keluar_details';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id_barang_keluar',
        'id_barang',
        'jumlah'

    ];

    protected $casts = [
        'jumlah' => 'integer',
        'deleted_at' => 'datetime', 
    ];


    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }
    public function barangKeluar()
    {
        return $this->belongsTo(Barangkeluar::class, 'id_barang_keluar', 'id');
    }
}
