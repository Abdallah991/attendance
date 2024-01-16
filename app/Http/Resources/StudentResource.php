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

        return [
            'id' => $this->id,
            'platformId' => $this->platformId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'cpr' => $this->cpr,
            'nationality' => $this->nationality,
            'email' => $this->email,
            'supportedByTamkeen' => $this->supportedByTamkeen,
            'acadamicQualification' => $this->acadamicQualification,
            'acadamicSpecialization' => $this->acadamicSpecialization,
            'scholarship' => $this->scholarship,
            'dob' => $this->dob,
            'cohortId' => $this->cohortId,
            'progressAt' => $this->progressAt,
            'AuditGiven' => $this->AuditGiven,
            'AuditReceived' => $this->AuditReceived,
            'userAuditRatio' => $this->userAuditRatio,
            'level' => $this->level,
            'auditDate' => $this->auditDate,
            'auditReceivedDate' => $this->auditReceivedDate,
            'auditGivenDate' => $this->auditGivenDate,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
