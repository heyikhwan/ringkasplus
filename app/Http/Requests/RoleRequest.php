<?php

namespace App\Http\Requests;

use App\Services\RoleService;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }
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
        $roleId = decode($this->role_permission);
        $role = $this->roleService->findById($roleId);
        $isDefaultRole = $role ? $this->roleService->isDefaultRole($role->name) : false;

        return [
            'name' => $isDefaultRole ? 'prohibited' : 'required|string|min:3|max:50|unique:roles,name,' . decode($this->role_permission),
            'permissions' => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama peran',
        ];
    }
}
