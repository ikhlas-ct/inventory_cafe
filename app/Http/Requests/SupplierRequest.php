<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class SupplierRequest extends FormRequest
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

        // Ambil ID supplier dari route (bisa null ketika create)
        $supplierId = $this->route('supplier') ? $this->route('supplier')->id : null;

        return [
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'foto' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Gunakan Rule::unique agar jelas dan aman
            'email' => [
                'required',
                'email',
                Rule::unique('suppliers', 'email')->ignore($supplierId),
            ],
        ];

    }
}
