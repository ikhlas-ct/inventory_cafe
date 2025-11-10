<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
      use HasFactory;

      protected $table = 'suppliers';
      protected $primaryKey = 'id';
      public $incrementing = true;
    protected $fillable = ['nama', 'telepon', 'alamat', 'email', 'foto'];

    public function barangMasuks()
        {
            return $this->hasMany(BarangMasuk::class, 'id_supplier','id');
        }

}
