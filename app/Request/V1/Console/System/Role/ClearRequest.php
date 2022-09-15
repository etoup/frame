<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\Role;

use Hyperf\Validation\Request\FormRequest;

class ClearRequest extends FormRequest
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
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'id' => '角色ID',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => '请选择操作项',
        ];
    }
}
