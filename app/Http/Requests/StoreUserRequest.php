<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            //the roles for this request
            'firstName' => ['required'],
            'lastName' => ['required'],
            'phone' => ['required'],
            'dob' => ['required'],
            'roleId' => ['required'],
            // make sure that the users have unique email
            // otherwise they will receive an error with  email being in use
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'min:6'],


        ];
    }
}
