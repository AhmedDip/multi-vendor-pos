<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'order_id'    => $this?->order_id,
            'product_id'  => $this?->product_id,
            'product_name'=> $this?->product?->name,
            'quantity'    => $this->quantity,
            'unit_price'  => $this->unit_price,
            'total_price' => $this->total_price,
            'assigned_to' => $this->assign_to,
            'sales_person' => UserDetailsResource::make($this?->assign_user),
        ];
    }
}
