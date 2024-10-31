<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('parent_id') == -1) {
            $this->merge(['parent_id' => null]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        if (!$this->input('parent_id')) {
            $body = -1;
        } else {
            $body = $this->input('parent_id');
        }

        return [
            'input-body-' . $body => 'required|string|max:255',
            'media' => 'nullable|image|max:2048',
            'parent_id' => [
                'nullable',
                'exists:comments,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $parentComment = Comment::find($value);
                        if ($parentComment && $parentComment->post_id !== $this->route('post')->id) {
                            $fail("Le commentaire selectionné n'apartient pas à ce post.");
                        }
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        if (!$this->input('parent_id')) {
            $body = -1;
        } else {
            $body = $this->input('parent_id');
        }
        return [
            'input-body-' . $body . '.max' => 'Le commentaire ne doit pas faire plus de 255 caractères.',
        ];
    }
}
