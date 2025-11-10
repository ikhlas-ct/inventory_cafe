<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satuan extends Model
{
      use HasFactory;

      protected $table = 'satuans';
      protected $primaryKey = 'id';
      public $incrementing = true;
      protected $fillable = ['nama', 'kode_satuan'];

      public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_satuan','id');
    }








}
