<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
       'username', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'deleted_at' => 'datetime'
        ];
    }


    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'id_user');
    }

    public function manajer()
    {
        return $this->hasOne(Manajer::class, 'id_user');
    }

    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class, 'id_karyawan', 'id');
    }

    public function barangKeluars()
    {
        return $this->hasMany(BarangKeluar::class, 'id_karyawan', 'id');
    }

    public function hasRole($roles)
{
    if (is_array($roles)) {
        return in_array($this->role, $roles);
    }

    return $this->role === $roles;
}

}
