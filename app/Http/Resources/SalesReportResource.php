<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesReportResource extends JsonResource
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
            'invoice_number'       => $this->invoice_number,
            'order_date'           => $this->order_date,
            'customer_name'        => $this?->customer?->name,
            'customer_phone'       => $this->customer?->phone,
            'product_items'        => OrderItemResource::collection($this->items),
            'total_amount'         => $this->total_amount,
            'discount_amount'      => $this->discount_amount,
            'total_payable_amount' => $this->total_payable_amount,
            'status'               => $this->status,
            'status_text'          => \App\Models\Order::STATUS_LIST[$this->status] ?? null,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
