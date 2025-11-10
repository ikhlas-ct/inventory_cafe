<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manajer extends Model
{
    protected $fillable = ['nama', 'telepon', 'alamat', 'foto', 'id_user'];
      protected $primaryKey = 'id';
    public $incrementing = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user','id');
    }
}
