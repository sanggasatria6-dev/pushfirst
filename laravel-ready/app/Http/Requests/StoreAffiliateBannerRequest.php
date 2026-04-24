<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAffiliateBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'placement' => ['required', 'in:home_hero,article_header,article_inline,article_footer'],
            'image_url' => ['required', 'url'],
            'target_url' => ['required', 'url'],
            'cta_text' => ['nullable', 'string', 'max:80'],
            'weight' => ['nullable', 'integer', 'between:1,100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
