<?php

declare(strict_types=1);

namespace App\Request\V1\Console\System\Notice;

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
            'title' => ['bail', 'required'],
            'content' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'title' => '标题',
            'content' => '内容',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => '请填写标题',
            'content.required' => '请填写内容',
        ];
    }
}
