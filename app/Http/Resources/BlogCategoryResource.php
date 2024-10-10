<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'shop_id'       => $this->shop_id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'description'   => $this->description,
            'parent_id'     => $this->parent_id,
            'display_order' => $this->display_order,
            'photo'         => get_image($this?->photo?->photo),
            'photo_name'       => $this?->photo?->photo,
        ];
    }
}
