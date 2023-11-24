<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment' => $this->comment,
            'commentedBy' => $this->commentedBy,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
