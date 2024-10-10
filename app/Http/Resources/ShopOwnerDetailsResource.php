<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopOwnerDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        $roles    = [];
        $roles_id = [];
        if (!empty($this->roles)) {
            foreach ($this->roles as $role) {
                $roles[]    = $role->name;
                $roles_id[] = $role->id;
            }
        }

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'status'     => User::STATUS_LIST[$this->status],
            'photo'      => get_image($this?->photo?->photo),
            'photo_name' => $this?->photo?->photo,
            'role'       => $roles,
            'role_id'    => $roles_id
        ];
    }
}
