<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;


class StoreCommentRequest extends FormRequest
{

    protected $parentId;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->input('parent_id')) {
            $this->parentId = -1;
        } else {
            $this->parentId = $this->input('parent_id');
        }

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

        return [
            'input-body-' . $this->parentId => 'required|string|max:255',
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

        return [
            'input-body-' . $this->parentId . '.max' => 'Le commentaire ne doit pas faire plus de 255 caractères.',
            'input-body-' . $this->parentId . '.required' => 'Un commentaire est requis.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $this->session()->flash('failed_id', $this->parentId);
        parent::failedValidation($validator);
    }
}
