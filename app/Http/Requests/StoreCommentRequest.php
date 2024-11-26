<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $formId = $this->input('parent_id');

        return [
            'input-body-' . $formId => 'required|string|max:255',
            'media' => 'nullable|image|max:2048',
            'parent_id' => [
                function ($attribute, $value, $fail) {
                    if ($value == -1) {
                        return;
                    }

                    if ($value) {
                        $parentComment = Comment::find($value);

                        if (!$parentComment || is_null($parentComment->content->id)) {
                            $fail("Commentaire supprimé ou introuvable.");
                            return;
                        }
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {

        return [
            'input-body-' . $this->input('parent_id') . '.max' => 'Le commentaire ne doit pas faire plus de 255 caractères.',
            'input-body-' . $this->input('parent_id') . '.required' => 'Un commentaire est requis.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(
            response()->json([
                'errors' => $errors,
                'form_id' => $this->getFormId(),
            ], 422)
        );
    }

    protected function getFormId()
    {
        return 'commentForm-' . $this->input('parent_id');
    }

}
