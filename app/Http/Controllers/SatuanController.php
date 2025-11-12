<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Satuan;
use App\Http\Requests\SatuanRequest;

class SatuanController extends Controller
{

   public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Satuan::query();

        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('kode_satuan', 'LIKE', "%{$search}%");
        }

        $satuans = $query->paginate(1);

        return view('pages.satuans.index', compact('satuans', 'search'));
    }

      public function store(SatuanRequest $request)
    {
        Satuan::create($request->validated());
        return redirect()->route('satuans.index')->with('success', 'Satuan created successfully.');
    }

    public function edit(Satuan $satuan)
    {
        return view('pages.satuans.edit', compact('satuan'));
    }

    public function update(SatuanRequest $request, Satuan $satuan)
    {
        $satuan->update($request->validated());
        return redirect()->route('satuans.index')->with('success', 'Satuan updated successfully.');
    }

      public function destroy(Satuan $satuan)
    {
        $satuan->delete();
        return redirect()->route('satuans.index')->with('success', 'Satuan deleted successfully.');
    }
}
