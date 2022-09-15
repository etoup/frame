<?php

declare(strict_types=1);

namespace App\Request\V1\Api\Common\Index;

use Hyperf\Validation\Request\FormRequest;

class TokenRequest extends FormRequest
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
            'code' => '微信CODE',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'code.required' => '参数错误',
        ];
    }
}
