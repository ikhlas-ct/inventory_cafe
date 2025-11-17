<?php

namespace App\Http\Controllers;

use App\Http\Requests\KaryawanRequest;
use App\Http\Requests\UserRequest;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
         $search = $request->query('search');
        $query = Karyawan::with('user');

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



        $karyawans = $query->paginate(10);
        return view('pages.karyawans.index', compact('karyawans'));
    }
    public function store(UserRequest $userRequest, KaryawanRequest $karyawanRequest)
    {
        $userValidated = $userRequest->validated();
        $userValidated['role'] = 'karyawan';
        $userValidated['password'] = Hash::make($userValidated['password']);  // Hash password

        $user = User::create($userValidated);

        $karyawanValidated = $karyawanRequest->validated();
        $karyawanValidated['id_user'] = $user->id;

        if ($userRequest->hasFile('foto')) {
            $karyawanValidated['foto'] = $userRequest->file('foto')->store('karyawan_fotos', 'public');
        }
        Karyawan::create($karyawanValidated);

        return redirect()->route('karyawans.index')->with('success', 'Karyawan dan User berhasil dibuat.');
    }

    public function edit(Karyawan $karyawan)
    {
        return view('pages.karyawans.edit', compact('karyawan'));
    }
    public function update(UserRequest $userRequest, KaryawanRequest $karyawanRequest, Karyawan $karyawan)
    {
        // Update User
        $user = $karyawan->user;
        $userValidated = $userRequest->validated();
        if (!empty($userValidated['password'])) {
            $userValidated['password'] = Hash::make($userValidated['password']);
        } else {
            unset($userValidated['password']);  // Jangan update kalau kosong
        }
        $user->update($userValidated);

        // Update Karyawan
        $karyawanValidated = $karyawanRequest->validated();

        // Handle foto
        if ($userRequest->hasFile('foto')) {
            if ($karyawan->foto) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            $karyawanValidated['foto'] = $userRequest->file('foto')->store('karyawan_fotos', 'public');
        }

        $karyawan->update($karyawanValidated);

        return redirect()->route('karyawans.index')->with('success', 'Karyawan dan User berhasil diupdate.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $user = $karyawan->user;

        if ($karyawan->foto) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $karyawan->delete();

        if ($user) {
            $user->delete();
        }

        return redirect()->route('karyawans.index')->with('success', 'Karyawan dan User berhasil dihapus.');
    }
}
