<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Requests\KategoriRequest;

class KategoriController extends Controller
{

    public function index()
    {
        $kategoris = Kategori::all();
        return view('pages.kategoris.index', compact('kategoris'));
    }

      public function store(KategoriRequest $request)
    {
        Kategori::create($request->validated());
        return redirect()->route('kategoris.index')->with('success', 'Kategori created successfully.');
    }

    public function edit(Kategori $Kategori)
    {
        return view('pages.kategoris.edit', compact('Kategori'));
    }

    public function update(KategoriRequest $request, Kategori $Kategori)
    {
        $Kategori->update($request->validated());
        return redirect()->route('kategoris.index')->with('success', 'Kategori updated successfully.');
    }

      public function destroy(Kategori $Kategori)
    {
        $Kategori->delete();
        return redirect()->route('kategoris.index')->with('success', 'Kategori deleted successfully.');
    }
}
