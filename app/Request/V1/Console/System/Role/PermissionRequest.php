<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\Role;

use Hyperf\Validation\Request\FormRequest;

class PermissionRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'code' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'code' => '角色标识',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'code.required' => '请选择操作项',
        ];
    }
}
