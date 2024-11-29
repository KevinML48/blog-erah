<?php

namespace App\Http\Requests;

use App\Helpers\ReservedUsernamesHelper;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
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
        $reservedUsernames = ReservedUsernamesHelper::getReservedUsernames();

        return [
            'username' => [
                'unique:users,username',
                'required',
                'string',
                'min:3',
                'max:15',
                function ($attribute, $value, $fail) use ($reservedUsernames) {
                    if (in_array(strtolower($value), $reservedUsernames)) {
                        $fail(__('validation.custom.' . $attribute . '.reserved'));
                    }
                },
                'regex:/^[a-zA-Z0-9_-]+$/',
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
}
