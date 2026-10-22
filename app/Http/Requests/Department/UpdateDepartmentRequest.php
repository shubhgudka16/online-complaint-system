<?php

namespace App\Http\Requests\Department;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var Department $department */
        $department = $this->route('department');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('departments', 'name')->ignore($department->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom attribute names for validation messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'department name',
            'description' => 'department description',
        ];
    }
}
