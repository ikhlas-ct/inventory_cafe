<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawans = Karyawan::with('user')->get();
        return view('pages.karyawans.index', compact('karyawans'));
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_user' => 'required|exists:users,id|unique:karyawans,id_user',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('karyawan_fotos', 'public');
        }

        Karyawan::create($validated);

        return redirect()->route('karyawans.index')->with('success', 'Karyawan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        $karyawan->load('user');
        return view('karyawans.show', compact('karyawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        $users = User::whereDoesntHave('karyawan')->orWhere('id', $karyawan->id_user)->get();
        return view('karyawans.edit', compact('karyawan', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_user' => 'required|exists:users,id|unique:karyawans,id_user,' . $karyawan->id,
        ]);

        if ($request->hasFile('foto')) {
            if ($karyawan->foto) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('karyawan_fotos', 'public');
        }

        $karyawan->update($validated);

        return redirect()->route('karyawans.index')->with('success', 'Karyawan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        if ($karyawan->foto) {
            Storage::disk('public')->delete($karyawan->foto);
        }
        $karyawan->delete();
        return redirect()->route('karyawans.index')->with('success', 'Karyawan deleted successfully.');
    }
}
