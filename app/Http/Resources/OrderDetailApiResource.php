<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        
        return[
            'order_id'             => $this->id,
            'invoice_number'       => $this?->invoice_number,
            'customer_id'          => $this->customer_id,
            'customer_name'        => $this?->customer?->name,
            'customer_phone'       => $this->customer_phone,
            'customer_address'     => $this?->customer?->address,
            'order_date'           => $this->order_date,
            'total_amount'         => $this->total_amount,
            'discount_amount'      => $this->discount_amount,
            'total_payable_amount' => $this?->total_payable_amount,
            'total_paid_amount'    => $this?->total_paid_amount,
            'order_status'         => $this?->status,
            'order_status_text'    => \App\Models\Order::STATUS_LIST[$this?->status] ?? 'N/A',
            'payment_status'       => $this->payment_status ?? 'N/A', 
            'note'                 => $this->note,
            'items'                => OrderItemResource::collection($this->items),
        ];
    }

}
