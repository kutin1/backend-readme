<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Введите текст комментария.',
            'content.string'   => 'Комментарий должен быть строкой.',
            'content.min'      => 'Комментарий должен содержать минимум 4 символа.',
        ];
    }
}
