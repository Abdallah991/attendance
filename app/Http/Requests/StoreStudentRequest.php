<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreStudentRequest extends FormRequest
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
            // 'id' => ['required'],
            // 'firstName' => ['required', 'sometimes'],
            // 'lastName' => ['required'],
            // 'platformId' => ['required'],
            // 'cpr' => ['required'],
            // 'acadamicSpecialization' => ['required'],
            // 'cohortId' => ['required'],





        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            // 'supportedByTamkeen' => $this->supportedByTamkeen,
            'cohortId' => $this->cohortId,
            // 'fcmToken' => $this->fcmToken,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,

        ]);
    }
}
