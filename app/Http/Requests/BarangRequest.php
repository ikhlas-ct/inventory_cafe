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
            'nama' => 'required|string|max:100',
            'id_kategori' => 'required|integer|exists:kategoris,id',
            'foto' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'id_satuan' => 'required|integer|exists:satuans,id',
            'deskripsi' => 'required|string|max:1000',
        ];
    }
}
