<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;

class RegisteredUserRequest extends FormRequest
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
        // Get reserved usernames dynamically
        $reservedUsernames = $this->getReservedUsernames();

        return [
            'name' => [
                'unique:users,name',
                'required',
                'string',
                'min:3',
                'max:35',
                function ($attribute, $value, $fail) use ($reservedUsernames) {
                    if (in_array(strtolower($value), $reservedUsernames)) {
                        $fail("Ce nom d\'utilisateur n'est pas disponible.");
                    }
                },
            ],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed',
                Password::min(12)
                ->letters()
                ->numbers()
                ->symbols()
                ->mixedCase()
                ->uncompromised(),
            ],
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Ce nom n\'est pas disponible.',
            'name.required' => 'Le nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.min' => 'Le nom d\'utilisateur doit faire au moins :min caractères.',
            'name.max' => 'Le nom d\'utilisateur ne peut pas dépasser :max caractères.',

            'email.required' => 'L\'email est obligatoire.',
            'email.string' => 'L\'email doit être une chaîne de caractères.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'email.lowercase' => 'L\'email doit être en minuscules.',
            'email.max' => 'L\'email ne peut pas dépasser  caractères.',
            'email.unique' => 'Cet email n\'est pas disponible.',

            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit comporter au moins :min caractères.',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un symbole.',
            'password.mixedCase' => 'Le mot de passe doit contenir à la fois des lettres majuscules et minuscules.',
            'password.uncompromised' => 'Le mot de passe a été compromis dans une violation de données connue.',

        ];
    }

    /**
     * Get reserved usernames based on registered routes.
     *
     * @return array
     */
    private function getReservedUsernames(): array
    {
        $reservedUsernames = [];

        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            if (preg_match('#^profil/([^/]+)#', $route->uri, $matches)) {
                $reservedUsernames[] = strtolower($matches[1]);
            }
        }

        return $reservedUsernames;
    }
}
