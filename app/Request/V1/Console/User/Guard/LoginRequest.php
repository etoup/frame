<?php

declare(strict_types=1);

namespace App\Request\V1\Console\User\Guard;

use Hyperf\Validation\Request\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => ['bail', 'required', 'size:11'],
            'password' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'username' => '手机号码',
            'password' => '密码',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'username.required' => '请填写手机号码',
            'username.size' => '手机号码长度：11字符',
            'password.required' => '请填写密码',
        ];
    }
}
