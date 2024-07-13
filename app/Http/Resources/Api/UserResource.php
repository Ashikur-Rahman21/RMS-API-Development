<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'user_type' => $this->when($request->routeIs('users.index'), $this->user_type),
            'photo' => $this->photo,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'address' => $this->address,            
        ];
    }
}
