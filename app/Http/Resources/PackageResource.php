<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'shop_id'=>$this->shop_id,
            'plan'=>$this->plan,
            'tagline'=>$this->tagline,
            'quota'=>$this->quota,
            'price'=>$this->price,
            'sort_id'=>$this->sort_id,
            'status' =>$this->status,
            'photo'=>$this?->photo?->photo,
        ];
    }
}
