<?php

declare(strict_types=1);

namespace App\Request\V1\Console\Project\Index;

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
            'name' => ['bail', 'required'],
            'duration' => ['bail', 'required'],
            'path' => ['bail', 'url']
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => '项目名称',
            'duration' => '项目使用时长',
            'path' => '项目链接地址',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => '请填写项目名称',
            'duration.required' => '请选择使用时长',
            'path.url' => '请填写正确地址',
        ];
    }
}
