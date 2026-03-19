<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class OnboardingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'surname' => ['required', 'string', 'min:2', 'max:100'],
            'company_name' => ['nullable', 'string', 'min:2', 'max:255'],
            'domain' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:domains,domain'],
        ];
    }
}
