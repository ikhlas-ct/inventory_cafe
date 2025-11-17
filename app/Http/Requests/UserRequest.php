<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->getCurrentUserIdForIgnore();

        $rules = [
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|max:100|unique:users,email',
        ];

        // CREATE
        if ($this->isMethod('POST')) {
            $rules['password'] = 'required|string|min:8';
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['password'] = 'nullable|string|min:8';

            if ($userId) {
                $rules['email'] .= ',' . $userId;
                $rules['username'] .= ',' . $userId;
            }
        }

        return $rules;
    }


    private function getCurrentUserIdForIgnore()
    {
        $model = $this->route('karyawan')
                ?? $this->route('manajer');

        return $model && $model->user ? $model->user->id : null;
    }
}
