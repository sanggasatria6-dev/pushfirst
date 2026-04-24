<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeoTopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['required', 'string', 'max:160'],
            'category' => ['required', 'in:buyer_guides,iot,informatics_learning'],
            'search_intent' => ['required', 'in:informational,commercial,transactional,navigational'],
            'language' => ['required', 'string', 'max:12'],
            'country_code' => ['required', 'string', 'size:2'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'country_code' => strtoupper((string) $this->input('country_code')),
        ]);
    }
}
