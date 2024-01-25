<?php

namespace App\Http\Resources;

//Miscellaneous
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'date'        => $this->date->format('d/m/Y'),
            'description' => $this->description,
            'value'       => $this->value,
            'user_id'     => $this->user_id
        ];
    }
}
