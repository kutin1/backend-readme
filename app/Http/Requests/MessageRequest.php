<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_id' => ['required', 'exists:users,id', 'different:sender_id'],
            'content' => ['required', 'string', 'min:1'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'sender_id' => auth()->id(),
        ]);
    }
}
