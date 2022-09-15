<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\Permission;

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
            'title' => ['bail', 'required'],
            'path' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'title' => '菜单名称',
            'path' => '路由地址',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => '请选择操作项',
            'title.required' => '请填写菜单名称',
            'path.required' => '请填写路由地址',
        ];
    }
}
