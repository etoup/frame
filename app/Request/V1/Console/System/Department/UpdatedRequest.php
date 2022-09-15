<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\Department;

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
            'contact' => ['bail', 'required', 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'id' => '部门ID',
            'name' => '部门名称',
            'contact' => '部门电话',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => '请选择操作项',
            'name.required' => '请填写部门名称',
            'contact.required' => '请填写部门电话',
            'contact.integer' => '部门电话请填写数字',
        ];
    }
}
