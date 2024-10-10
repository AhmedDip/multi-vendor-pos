<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
                'id'                  => $this->id,
                'model_name'          => 'Product',
                'name'                => $this->name,
                'slug'                => $this->slug,
                'type'                => $this->type,
                'type_name'           => \App\Models\Product::TYPE_LIST[$this->type],
                'attribute_values'    => $this->getAttributeValuesIds() ?? [],
                'selected_attributes' => $this->selectedAttributeNameAndValues() ?? [],
                'price'               => $this->price,
                'cost_price'          => $this->cost_price,
                'sku'                 => $this->sku,
                'category'            => $this?->category?->name,
                'category_id'         => $this->category_id,
                'brand'               => $this?->brand?->name,
                'brand_id'            => $this->brand_id,
                'warehouse'           => $this?->warehouse?->name,
                'warehouse_id'        => $this->warehouse_id,
                'shelf_location'      => $this->shelf_location,
                'stock'               => $this->stock,
                'status'              => \App\Models\Product::STATUS_LIST[$this->status],
                'status_id'           => $this->status,
                'sort_order'          => $this->sort_order,
                'shop'                => $this?->shop?->name,
                'shop_id'             => $this->shop_id,
                'photo'               => get_image($this?->photo?->photo),
                'photo_name'          => $this?->photo?->photo,
        ];
    }
}
