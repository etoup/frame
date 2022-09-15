<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\User;

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
            'mobile' => ['bail', 'required'],
            'real_name' => ['bail', 'required'],
            'department_id' => ['bail', 'required'],
            'role' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'id' => '用户ID',
            'mobile' => '用户账号',
            'real_name' => '用户姓名',
            'department_id' => '所属部门',
            'role' => '用户角色',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => '请选择操作项',
            'mobile.required' => '请填写用户账号',
            'real_name.required' => '请填写用户姓名',
            'department_id.required' => '请选择所属部门',
            'role.required' => '请选择用户角色',
        ];
    }
}
