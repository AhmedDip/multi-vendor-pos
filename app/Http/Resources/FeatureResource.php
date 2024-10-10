<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'          =>$this->id,
            'shop_id'     =>$this->shop_id,
            'name'        =>$this->name,
            'slug'        =>$this->slug,
            'status'      =>$this->status,
            'sort_order'  =>$this->sort_order,
        ];
    }
}
