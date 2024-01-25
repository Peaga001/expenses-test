<?php

namespace App\Http\Resources;

//Miscellaneous
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'name'         => $this->name,
            'email'        => $this->email,
            'access_token' => $this->token
        ];
    }
}
