<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['required', 'string', 'min:1'],
        ];
    }

    // protected function prepareForValidation()
    // {
    //     $this->merge([
    //         'q' => trim($this->q),
    //     ]);
    // }

    protected function prepareForValidation()
    {
        $value = $this->input('q');

        if (is_array($value)) {
            return;
        }

        if ($value === null) {
            return;
        }

        $value = trim((string)$value);

        $this->merge([
            'q' => $value,
        ]);
    }
}
