<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public function prepareForValidation()
    {
        if ($this->has('publication_time')) {
            $userTime = $this->input('publication_time');
            $timezone = $this->input('timezone', 'UTC');
            $utcTime = Carbon::parse($userTime, $timezone)->setTimezone('UTC');
            $this->merge([
                'publication_time' => $utcTime->format('Y-m-d H:i:s'),
            ]);
        }
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'body' => 'required',
            'publication_time' => 'nullable|date|after_or_equal:now',
            'media_type' => 'required|in:image,video',
            'media' => 'nullable|file|image|max:2048',
            'video_link' => 'nullable|url',
            'theme_id' => 'required|exists:themes,id'
        ];
    }
}
