<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'model_name'=>'AttributeValue',
            'name'=>$this->name,
            'slug'=>$this->slug,
            'shop'=>$this->shop_id,
            'status'=>$this->status,
            'sort_order'=>$this->sort_order,
            'description'=>$this->description,
            'attribute_id'=>$this->attribute_id,
            'attribute_name'=>$this?->attribute?->name,
        ];
    }
}
