<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'profile-picture' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'firtName' => 'required',
            'lastName' => 'required',
            'pseudonyme' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
            'password-confirm' => 'required|same:password',
            'rgpd' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'profile-picture.image' => 'Le fichier doit être une image',
            'profile-picture.mimes' => 'Les formats autorisés sont jpeg, png, jpg, webp, svg',
            'profile-picture.max' => 'La taille maximale de l\'image est de 2 Mo',
            'firtName.required' => 'Le nom est obligatoire',
            'lastName.required' => 'Le prénom est obligatoire',
            'pseudonyme.required' => 'Le pseudonyme est obligatoire',
            'pseudonyme.unique' => 'Ce pseudonyme est déjà utilisé',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email est invalide',
            'email.unique' => 'Cet email est déjà utilisé',
            'password.required' => 'Le mot de passe est obligatoire',
            'password-confirm.required' => 'La confirmation du mot de passe est obligatoire',
            'password-confirm.same' => 'Les mots de passe ne correspondent pas',
            'rgpd.required' => 'Vous devez accepter les CGV',
        ];
    }

    /**
     * Indique si les données du formulaire doivent être "flashées" à la session lors d'une validation échouée.
     */
    public function withInput()
    {
        return true;
    }
}
