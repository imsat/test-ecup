<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'identifier' => (string)$this->_id,
            'user_name' => $this->name,
            'user_email' => $this->email,
            'user_gender' => $this->gender,
            'status' => $this->status,
            'email_verified_date' => $this->created_at,
            'creation_date' => $this->created_at,
            'last_change_date' => $this->updated_at,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $this->id)
                ]
            ]
        ];
    }
}
