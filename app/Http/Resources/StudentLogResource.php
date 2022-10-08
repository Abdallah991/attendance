<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'studentId'=> $this->student_id,
            'log'=> $this->log,
            'createdAt'=> $this->created_at,
            'updatedAt'=> $this->updatedAt,
        ];
    }
}
