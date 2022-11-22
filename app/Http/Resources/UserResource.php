<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'position' => $this->position,
            'joinDate' => $this->joinDate,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'fcmToken' => $this->fcmToken,
            'dob' => $this->dob,
            'permission' => $this->permission,
            'email' => $this->email,
            'password' => $this->password,
            'rememberToken' => $this->rememberToken,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
