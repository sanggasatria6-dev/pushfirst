<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMicrosaasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'alpha_dash', 'max:80', Rule::unique('microsaas', 'slug')],
            'tagline' => ['nullable', 'string', 'max:190'],
            'description' => ['nullable', 'string'],
            'frontend_build' => ['required', 'file', 'mimes:zip'],
            'backend_base_url' => ['required', 'url', 'max:255'],
            'price_label' => ['nullable', 'string', 'max:80'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }
}
