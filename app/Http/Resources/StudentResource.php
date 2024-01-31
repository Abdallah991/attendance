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
            'sponsorship' => $this->sponsorship,
            'acadamicQualification' => $this->acadamicQualification,
            'acadamicSpecialization' => $this->acadamicSpecialization,
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
            'profilePicture' => $this->profilePicture,
            'cprPicture' => $this->cprPicture,
            'pictureChanged' => $this->pictureChanged,
            'cprChanged' => $this->cprChanged,
            'maritalStatus' => $this->maritalStatus,
            'highestDegree' => $this->highestDegree,
            'academicInstitute' => $this->academicInstitute,
            'graduationDate' => $this->graduationDate,
            'currentJobTitle' => $this->currentJobTitle,
            'companyNameAndCR' => $this->companyNameAndCR,
            'sp' => $this->sp,
            'sp' => $this->sp,
            'occupation' => $this->occupation,
            'unipal' => $this->unipal,
            'discord' => $this->discord,
            'trainMe' => $this->trainMe,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
