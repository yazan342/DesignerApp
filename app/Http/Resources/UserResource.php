<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function __construct($user, $token)
    {
        $this->resource = $user;
        $this->token = $token;
    }

    public function toArray($request)
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'address' => $this->address,
                'is_designer' => $this->is_designer,
            ],
            'token' => $this->token,
        ];
    }
}
