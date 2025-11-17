<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\SupplierRequest;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index( Request $request )
    {
        $search = $request->query('search');
        $query = Supplier::query();

        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('telepon', 'LIKE', "%{$search}%")
                  ->orWhere('alamat', 'LIKE', "%{$search}%");
        }

        $suppliers = $query->paginate(10);

        return view('pages.suppliers.index', compact('suppliers', 'search'));
    }

      public function store(SupplierRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('supplier_foto', 'public');
        }

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function edit(Supplier $supplier)
    {
        return view('pages.suppliers.edit', compact('supplier'));

    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            if ($supplier->foto) {
                Storage::disk('public')->delete($supplier->foto);
            }
            $validated['foto'] = $request->file('foto')->store('supplier_foto', 'public');
        }
        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

      public function destroy(Supplier $supplier)
    {
           if ($supplier->foto) {
            Storage::disk('public')->delete($supplier

            ->foto);
        }
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
