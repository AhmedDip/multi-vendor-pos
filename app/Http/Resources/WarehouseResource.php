<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return[
            'id'=>$this->id,
            'model_name'  =>'Warehouse',
            'shop_id'     =>$this->shop_id,
            'name'        =>$this->name,
            'slug'        =>$this->slug,
            'phone'       =>$this->phone,
            'street_address'=>$this->street_address,
            'status'      =>$this->status,
            'sort_order'  =>$this->sort_order,
            'photo'       =>get_image($this?->photo?->photo),
        ];
    }
}
