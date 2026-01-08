<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['nama', 'telepon', 'alamat', 'email', 'foto'];
    protected $casts = ['email' => 'string'];
    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_supplier', 'id');
    }
}
