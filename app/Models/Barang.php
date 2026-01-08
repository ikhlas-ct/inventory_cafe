<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barangs';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id_supplier',
        'id_kategori',
        'id_satuan',
        'kode_barang',
        'nama',
        'foto',
        'harga',
        'deskripsi'
    ];

    protected $casts = [
        'harga' => 'decimal:2'
    ];

    protected $appends = ['stok_sekarang'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan', 'id');
    }

    public function barangMasuksDetail()
    {
        return $this->hasMany(BarangMasukDetail::class, 'id_barang', 'id');
    }

    public function barangKeluarDetail()
    {
        return $this->hasMany(BarangKeluarDetail::class, 'id_barang', 'id');
    }
    public function getStokSekarangAttribute()
    {
        return $this->barangMasuksDetail()->sum('jumlah_tersisa');
    }
}
