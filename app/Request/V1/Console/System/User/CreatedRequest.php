<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\User;

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
            'mobile' => ['bail', 'required'],
            'real_name' => ['bail', 'required'],
            'password' => ['bail', 'required'],
            'confirm' => ['bail', 'required'],
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
            'mobile' => '用户账号',
            'real_name' => '用户姓名',
            'password' => '用户密码',
            'confirm' => '确认密码',
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
            'mobile.required' => '请填写用户账号',
            'real_name.required' => '请填写用户姓名',
            'password.required' => '请填写用户密码',
            'confirm.required' => '请填写确认密码',
            'department_id.required' => '请选择所属部门',
            'role.required' => '请选择用户角色',
        ];
    }
}
