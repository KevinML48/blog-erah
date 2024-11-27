<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemeRequest extends FormRequest
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
        $themeId = $this->route('theme')?->id ?? null;

        return [
            'name' => 'required|string|max:30',
            'slug' => [
                'required',
                'string',
                'max:30',
                "unique:themes,slug,{$themeId}",
                'regex:/^[a-zA-Z0-9_-]+$/',
            ],
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le champ nom est requis.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne peut pas dépasser :max caractères.',
            'slug.required' => 'Le champ slug est requis.',
            'slug.string' => 'Le slug doit être une chaîne de caractères.',
            'slug.max' => 'Le slug ne peut pas dépasser :max caractères.',
            'slug.unique' => 'Ce slug est déjà utilisé.',
            'slug.regex' => 'Le slug peut contenir uniquement des lettres, des chiffres, des tirets et des underscores, sans espaces ni slashs.',
        ];
    }
}
