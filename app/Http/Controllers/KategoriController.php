<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Requests\KategoriRequest;

class KategoriController extends Controller
{

public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Kategori::query();

        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
        }

        $kategoris = $query->paginate(10); // Paginate with 10 items per page

        return view('pages.kategoris.index', compact('kategoris', 'search'));
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
