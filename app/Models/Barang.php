<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
        use HasFactory;

      protected $table = 'barangs';
      protected $primaryKey = 'id';
      public $incrementing = true;
      protected $fillable = [
        'nama', 'id_kategori', 'id_satuan', 'harga', 'deskripsi', 'foto','stok'];

        protected $casts = [
        'harga' => 'decimal:2'
        ];

    protected $appends = ['stok_sekarang'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori','id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan','id');
    }

    public function barangMasuks()
    {
        return $this->hasMany(Barangmasuk::class, 'id_barang','id');
    }

    public function barangKeluars()
    {
        return $this->hasMany(Barangkeluar::class, 'id_barang','id');
    }
    public function getStokSekarangAttribute()
    {
        return $this->stok;
    }



}
