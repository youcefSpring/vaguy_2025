<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstagramScrubRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9._]+$/',
                'not_regex:/^[._]/',
                'not_regex:/[._]$/',
                'not_regex:/[._]{2,}/'
            ],
            'token' => 'required|string|min:10',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Instagram username is required.',
            'username.min' => 'Instagram username must be at least 3 characters.',
            'username.max' => 'Instagram username cannot exceed 30 characters.',
            'username.regex' => 'Instagram username can only contain letters, numbers, dots, and underscores.',
            'username.not_regex' => 'Instagram username cannot start or end with dots or underscores, and cannot contain consecutive dots or underscores.',
            'token.required' => 'API token is required.',
            'token.min' => 'API token must be at least 10 characters.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'username' => 'Instagram username',
            'token' => 'API token',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Remove @ symbol if present
        if ($this->has('username')) {
            $this->merge([
                'username' => ltrim($this->username, '@'),
            ]);
        }
    }

    /**
     * Get the validated data with additional processing.
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        // Additional processing for the username
        if (isset($validated['username'])) {
            $validated['username'] = strtolower(trim($validated['username']));
        }

        return $validated;
    }
}