<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Manajer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'manajers';
    protected $fillable = ['id_user', 'nama', 'telepon', 'alamat', 'foto'];
    protected $primaryKey = 'id';
    public $incrementing = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
