<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\Role;

use Hyperf\Validation\Request\FormRequest;

class BindRequest extends FormRequest
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
            'id' => ['bail', 'required'],
            'permissions' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'id' => '角色ID',
            'permissions' => '权限ID集合',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => '请选择操作项',
            'permissions.required' => '请选择要绑定的权限',
        ];
    }
}
