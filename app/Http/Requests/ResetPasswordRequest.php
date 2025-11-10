<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
            'password-confirm' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Le mot de passe est obligatoire',
            'password-confirm.required' => 'La confirmation du mot de passe est obligatoire',
            'password-confirm.same' => 'Les mots de passe ne correspondent pas',
        ];
    }
}
