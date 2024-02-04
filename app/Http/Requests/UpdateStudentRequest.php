<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdateStudentRequest extends FormRequest
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
                'firstName' => ['sometimes'],
                'lastName' => ['sometimes'],
                'id' => ['required'],
                'platformId' => ['sometimes'],
                'firstName' => ['sometimes'],
                'lastName' => ['sometimes'],
                'email' => ['sometimes'],
                'phone' => ['sometimes'],
                'gender' => ['sometimes'],
                'nationality' => ['sometimes'],
                'cpr' => ['sometimes'],
                'dob' => ['sometimes'],
                'acadamicQualification' => ['sometimes'],
                'acadamicSpecialization' => ['sometimes'],
                'cohortId' => ['sometimes'],

            ];
        } else {
            // this is the PATCH request
            return [
                //
                'firstName' => ['sometimes'],
                'lastName' => ['sometimes'],
                'id' => ['required'],
                'platformId' => ['sometimes'],
                'firstName' => ['sometimes'],
                'lastName' => ['sometimes'],
                'email' => ['sometimes'],
                'phone' => ['sometimes'],
                'gender' => ['sometimes'],
                'nationality' => ['sometimes'],
                'dob' => ['sometimes'],
                'acadamicQualification' => ['sometimes'],
                'acadamicSpecialization' => ['sometimes'],
                'cohortId' => ['sometimes'],

            ];
        }
    }
}
