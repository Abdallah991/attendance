<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'platformId' => ['required'],
            'commentedBy' => ['required'],
            'comment' => ['required'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'platformId' => $this->platformId,
            'commentedBy' => $this->commentedBy,
            'comment' => $this->comment,

        ]);
    }
}
