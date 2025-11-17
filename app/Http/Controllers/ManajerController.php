<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManajerRequest;
use App\Http\Requests\UserRequest;
use App\Models\Manajer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ManajerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Manajer::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $manajers = $query->paginate(10);

        return view('pages.manajers.index', compact('manajers', 'search'));
    }

 
    public function store(UserRequest $userRequest, ManajerRequest $manajerRequest)
    {
        // Validate and create User
        $userValidated = $userRequest->validated();
        $userValidated['role'] = 'manajer';
        $userValidated['password'] = Hash::make($userValidated['password']);

        $user = User::create($userValidated);

        // Validate and create Manajer
        $manajerValidated = $manajerRequest->validated();
        $manajerValidated['id_user'] = $user->id;

        if ($userRequest->hasFile('foto')) {
            $manajerValidated['foto'] = $userRequest->file('foto')->store('manajer_fotos', 'public');
        }

        Manajer::create($manajerValidated);

        return redirect()->route('manajers.index')->with('success', 'Manajer dan User berhasil dibuat.');
    }

    public function edit(Manajer $manajer)
    {
        return view('pages.manajers.edit', compact('manajer'));
    }

    public function update(UserRequest $userRequest, ManajerRequest $manajerRequest, Manajer $manajer)
    {
        // Update User
        $user = $manajer->user;
        $userValidated = $userRequest->validated();
        if (!empty($userValidated['password'])) {
            $userValidated['password'] = Hash::make($userValidated['password']);
        } else {
            unset($userValidated['password']);
        }
        $user->update($userValidated);

        // Update Manajer
        $manajerValidated = $manajerRequest->validated();

        if ($userRequest->hasFile('foto')) {
            if ($manajer->foto) {
                Storage::disk('public')->delete($manajer->foto);
            }
            $manajerValidated['foto'] = $userRequest->file('foto')->store('manajer_fotos', 'public');
        }

        $manajer->update($manajerValidated);

        return redirect()->route('manajers.index')->with('success', 'Manajer dan User berhasil diupdate.');
    }

    public function destroy(Manajer $manajer)
    {
        $user = $manajer->user;

        if ($manajer->foto) {
            Storage::disk('public')->delete($manajer->foto);
        }

        $manajer->delete();

        if ($user) {
            $user->delete();
        }

        return redirect()->route('manajers.index')->with('success', 'Manajer dan User berhasil dihapus.');
    }
}
