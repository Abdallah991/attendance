<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'date' => $this->date,
            'time' => $this->time,
            'location' => $this->location,
            'status' => $this->status,
            'type' => $this->type,
            'image' => $this->image,
            'host' => $this->host,
            'guest' => $this->guest,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,

        ];
    }
}
