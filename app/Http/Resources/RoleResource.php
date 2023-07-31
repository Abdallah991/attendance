<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'name' => $this->name,
        ];
    }
}
