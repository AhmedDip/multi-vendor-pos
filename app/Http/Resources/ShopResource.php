<?php

namespace App\Http\Resources;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'email'       => $this->email,
            'phone'       => $this->phone,
            'description' => $this->description,
            'status'      => Shop::STATUS_LIST[$this->status] ?? 'N/A',
            'address'     => $this->address,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            // 'created_by'  => $this->created_by,
            // 'updated_by'  => $this->updated_by,
            'photo'       => get_image($this?->photo?->photo),
            'photo_name'       => $this?->photo?->photo,
        ];
    }
}
