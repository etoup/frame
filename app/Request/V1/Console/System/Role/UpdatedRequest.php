<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\Role;

use Hyperf\Validation\Request\FormRequest;

class UpdatedRequest extends FormRequest
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
            'id' => '角色ID',
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
            'id.required' => '请选择操作项',
            'name.required' => '请填写角色名称',
            'code.required' => '请填写角色标识',
            'code.alpha' => '角色标识必须是字母',
        ];
    }
}
