<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePostRequest extends FormRequest
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
        $existingPublicationTime = $this->route('post')->publication_time;

        if ($this->has('publication_time') && !empty($this->input('publication_time'))) {
            $userTime = $this->input('publication_time');
            $timezone = $this->input('timezone', 'UTC');
            $utcTime = Carbon::parse($userTime, $timezone)->setTimezone('UTC');
            $this->merge([
                'publication_time' => $utcTime->format('Y-m-d H:i:s'),
            ]);
        } else {
            $this->merge([
                'publication_time' => Carbon::parse($existingPublicationTime)->format('Y-m-d H:i:s'),
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
        $rules = [
            'title' => 'required|max:255',
            'body' => 'required',
            'media_type' => 'required|in:image,video',
            'media' => 'nullable|file|image|max:2048',
            'video_link' => 'nullable|url',
            'theme_id' => 'required|exists:themes,id',
            'publication_time' => ['required', 'date']
        ];

        $publicationTime = $this->route('post')->publication_time;

        $storedTime = Carbon::parse($publicationTime)->utc();
        $requestTime = Carbon::parse($this->input('publication_time'))->utc();

        if ($storedTime->isFuture()) {
            $rules['publication_time'][] = 'after_or_equal:now';
        } else {
            $rules['publication_time'][] = function ($attribute, $value, $fail) use ($storedTime, $requestTime) {
                if (!$storedTime->equalTo($requestTime)) {
                    $fail("On ne peut pas modifier la date d'un post déjà publié.");
                }
            };
        }

        return $rules;
    }



}
