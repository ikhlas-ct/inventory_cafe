<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
      use HasFactory, SoftDeletes;

      protected $table = 'kategoris';
      protected $primaryKey = 'id';
      public $incrementing = true;
      protected $fillable = ['nama', 'deskripsi'];


    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_kategori','id');
    }
}
