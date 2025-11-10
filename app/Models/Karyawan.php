<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
        use HasFactory;

      protected $table = 'karyawans';
      protected $primaryKey = 'id';
      public $incrementing = true;
protected $fillable = ['nama', 'telepon', 'alamat', 'foto', 'id_user'];
public function user()
    {
        return $this->belongsTo(User::class, 'id_user','id');
    }
    public function barangMasuks()
        {
            return $this->hasMany(BarangMasuk::class, 'id_karyawan','id');
        }

        public function barangKeluars()
    {
        return $this->hasMany(BarangKeluar::class, 'id_karyawan','id');
    }
}
