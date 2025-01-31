<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Autoriser cette requête, par défaut true
    }

    public function rules()
    {
        return [
            'identifiant' => ['required', 'regex:/^[a-zA-Z0-9-.-@]+$/i'],
            'password' => 'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'identifiant.regex' => 'L’identifiant doit être alphanumérique ou un email valide.',
            'identifiant.email' => 'L’identifiant doit être alphanumérique ou un email valide.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.'
        ];
    }
}

