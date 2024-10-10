<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'attribute_id'         => $this->attribute_id,
            'attribute_name'       => $this->attribute->name,
            'attribute_value_name' => $this->name,
            'attribute_value_slug' => $this->slug,
            'status'               => $this->status,
            'sort_order'           => $this->sort_order,
        ];
    }
}
