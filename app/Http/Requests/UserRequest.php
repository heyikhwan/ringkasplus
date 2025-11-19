<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|min:3|max:255',
            'username' => 'required|string|min:3|max:50|alpha_dash|unique:users,username,' . decode($this->user),
            'email' => 'nullable|string|email:rfc,dns|min:3|max:255',
            'is_active' => 'nullable|boolean',
            'photo' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'roles' => 'nullable|array'
        ];

        if ($this->method() == 'POST') {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'photo' => 'foto profil'
        ];
    }
}
