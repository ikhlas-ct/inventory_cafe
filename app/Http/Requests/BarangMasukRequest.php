<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangMasukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
            return [
            'id_barang' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
            'catatan' => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'id_karyawan' => 'required|string|exists:karyawans,id',
            'id_supplier' => 'required|string|exists:suppliers,id',
        ];
    }
}
