<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdateCommentRequest extends FormRequest
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
        $method = $this->method();
        // for put method
        if ($method == 'PUT') {

            return [
                //
                'comment' => ['required'],
                'commentedBy' => ['sometimes'],
                'platformId' => ['sometimes'],


            ];
        } else {
            // this is the PATCH request
            return [
                'comment' => ['required'],
                'commentedBy' => ['sometimes'],
                'platformId' => ['sometimes'],

            ];
        }
    }
}
