<?php

namespace App\Http\Requests;

use App\Models\CommentStructure;
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'body' => 'required|string|max:255',
            'parent_id' => [
                'nullable',
                'exists:comments_structure,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $parentComment = CommentStructure::find($value);
                        if ($parentComment && $parentComment->post_id !== $this->route('post')->id) {
                            $fail("Le parent selectionné n'apartient pas à ce post.");
                        }
                    }
                },
            ],
        ];
    }
}
