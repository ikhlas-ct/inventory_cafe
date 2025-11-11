<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangRequest extends FormRequest
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
        'nama' => 'required|string|max:255',
        'stok' => 'required|integer|min:0',
        'id_kategori' => 'required|integer|exists:kategori,id',
        'foto' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'id_satuan' => 'required|integer|exists:satuan,id',
        'harga' => 'required|numeric|min:0',
        'deskripsi' => 'required|string|max:1000',
    ];
    
    }
}
