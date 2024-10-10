<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPdfResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'order_id'         => $this->id,
            'invoice_number'   => $this?->invoice_number,
            'customer_id'      => $this->customer_id,
            'customer_name'    => $this?->customer?->name,
            'customer_phone'   => $this->customer_phone,
            'customer_address' => $this?->customer?->address,
            'shop_id'          => $this->shop_id,
            'shop_name'        => $this?->shop?->name,
            'shop_phone'       => $this?->shop?->phone,
            'shop_address'     => $this?->shop?->address,
            'shop_email'       => $this?->shop?->email,
            'order_date'       => $this->order_date,
            'total_amount'     => $this->total_amount,
            'discount_amount'  => $this->discount_amount,
            'note'             => $this->note,
            'product_items'    => OrderItemResource::collection($this->items),
            'transactions'     => TransactionResource::collection($this->transactions),
            'order_summary'    => $this->getOrderSummaryAttribute(),
        ];
    }
}
