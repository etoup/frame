<?php

declare(strict_types=1);

namespace App\Request\V1\Console\User\Guard;

use Hyperf\Validation\Request\FormRequest;

class CodeRequest extends FormRequest
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
            'mobile' => ['bail', 'required', 'size:11'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'mobile' => '手机号码',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'mobile.required' => '请填写手机号码',
            'mobile.size' => '手机号码长度：11字符',
        ];
    }
}
