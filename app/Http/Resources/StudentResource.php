<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    //  transform the response to JSON in the format you want.
    // exclude any values 
    public function toArray($request)
    {

        // Transform the response in the way you wish for
        // ! => means : in javascript context
        // ! -> means . in javascript context
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'nationality' => $this->nationality,
            'email' => $this->email,
            'supportedByTamkeen' => $this->supported_by_tamkeen,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'fcmToken' => $this->fcm_token,
            'dob' => $this->dob,
            'cohortId' => $this->cohort_id,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
