<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\Notice;

use Hyperf\Validation\Request\FormRequest;

class DeletedRequest extends FormRequest
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
            'key' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'key' => 'KEY',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'key.required' => '请选择操作项',
        ];
    }
}
