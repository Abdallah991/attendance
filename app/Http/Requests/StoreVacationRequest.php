<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreVacationRequest extends FormRequest
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
            'studentId' => ['required'],
            'platformId' => ['required'],
            'author' => ['required'],
            'description' => ['required'],
            'startDate' => ['required'],
            'endDate' => ['required'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'studentId' => $this->studentId,
            'platformId' => $this->platformId,
            'author' => $this->author,
            'description' => $this->description,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,

        ]);
    }
}
