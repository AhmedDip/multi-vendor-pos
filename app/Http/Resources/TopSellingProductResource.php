<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopSellingProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        $product = $this->product;
        return [
            'total_sold' => $this->total_sold,
            'product_id' => $product ? $product->id : null,
            'name'       => $product ? $product->name : null,
            'sku'        => $product ? $product->sku : null,
            'slug'       => $product ? $product->slug : null,
            'image'      => $product && $product->photo ? get_image($product->photo->photo) : null,
            'photo_name' => $product && $product->photo ? $product->photo->photo : null,
        ];
    }
}
