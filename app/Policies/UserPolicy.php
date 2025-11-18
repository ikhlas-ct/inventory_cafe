<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;


    public function iskaryawan(User $user)
    {
        return $user->role === 'karyawan';
    }

    public function ismanajer(User $user)
    {
        return $user->role === 'manajer';
    }

}
