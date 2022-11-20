<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        // this is the put method
        // TODO: make sure regarding the password
        // TODO: make sure regarding the required and sometimes fields
        if ($method == 'PUT') {

            return [
                //
                'firstName' => ['required', 'sometimes'],
                'lastName' => ['required', 'sometimes'],

            ];
        } else {
            // this is the PATCH request
            // TODO: make sure regarding the password
            // TODO: make sure regarding the required and sometimes fields
            return [
                //
                'firstName' => ['required', 'sometimes'],
                'lastName' => ['required', 'sometimes'],

            ];
        }
    }
}
