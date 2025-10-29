<?php

namespace App\Http\Requests;

use App\Lib\GlobalConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRegistrationRequest extends FormRequest
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
        $countryData = (array) json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',', array_column($countryData, 'dial_code'));
        $countries = implode(',', array_column($countryData, 'country'));

        // Get password validation rule from settings
        $general = gs();
        $passwordValidation = Password::min(6);
        if ($general->secure_password) {
            $passwordValidation = Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised();
        }

        // Agreement validation
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }

        return [
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|regex:/^([0-9]*)$/',
            'password' => ['required', 'confirmed', $passwordValidation],
            'username' => 'required|unique:users|min:6|max:30|alpha_dash',
            'captcha' => 'sometimes|required',
            'mobile_code' => 'required|in:' . $mobileCodes,
            'country_code' => 'required|in:' . $countryCodes,
            'country' => 'required|in:' . $countries,
            'agree' => $agree,
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'username.unique' => 'This username is already taken.',
            'username.alpha_dash' => 'Username can only contain letters, numbers, dashes and underscores.',
            'mobile.regex' => 'Mobile number must contain only digits.',
            'password.confirmed' => 'Password confirmation does not match.',
            'agree.required' => 'You must agree to the terms and conditions.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'firstname' => 'first name',
            'lastname' => 'last name',
            'mobile_code' => 'mobile country code',
            'country_code' => 'country code',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => strtolower($this->username),
            'email' => strtolower($this->email),
            'firstname' => ucfirst($this->firstname),
            'lastname' => ucfirst($this->lastname),
        ]);
    }
}