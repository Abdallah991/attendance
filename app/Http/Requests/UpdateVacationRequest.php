<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdateVacationRequest extends FormRequest
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
                'id' => ['required'],
                'platformId' => ['sometimes'],
                'studentId' => ['sometimes'],
                'startDate' => ['required'],
                'endDate' => ['required'],
                'description' => ['sometimes'],
                'author' => ['required'],


            ];
        } else {
            // this is the PATCH request
            return [
                'id' => ['required'],
                'platformId' => ['sometimes'],
                'studentId' => ['sometimes'],
                'startDate' => ['required'],
                'endDate' => ['required'],
                'description' => ['sometimes'],
                'author' => ['required'],

            ];
        }
    }
}
