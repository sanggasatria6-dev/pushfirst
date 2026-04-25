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
            'keyword' => ['nullable', 'string', 'max:160'],
            'category' => ['required', 'in:urban_farming,informatics_learning,business_growth'],
            'search_intent' => ['nullable', 'in:informational,commercial,transactional,navigational'],
            'language' => ['nullable', 'string', 'max:12'],
            'country_code' => ['nullable', 'string', 'size:2'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'country_code' => strtoupper((string) $this->input('country_code', config('portal.seo.default_country_code', 'ID'))),
            'language' => (string) $this->input('language', config('portal.seo.default_language', 'id')),
        ]);
    }
}
