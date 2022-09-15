<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\Role;

use Hyperf\Validation\Request\FormRequest;

class CreatedRequest extends FormRequest
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
            'name' => ['bail', 'required'],
            'code' => ['bail', 'required', 'alpha'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => '角色名称',
            'code' => '角色标识',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => '请填写角色名称',
            'code.required' => '请填写角色标识',
            'code.alpha' => '角色标识必须是字母',
        ];
    }
}
