<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'invoice_number'     => $this?->invoice_number,
            'customer_id'        => $this->customer_id,
            'customer_name'      => $this?->customer?->name,
            'customer_phone'     => $this->customer_phone,
            'order_date'         => $this->order_date,
            'total_amount'       => $this->total_amount,
            'discount_amount'    => $this->discount_amount,
            'note'               => $this->note,
            'payment_status'     => $this->getPaymentStatusAttribute(),
            'transaction_button' => $this->getTransactionButton(),
            'status'             => \App\Models\Order::STATUS_LIST[$this->status],
            'items'              => OrderItemResource::collection($this->items),
            'transactions'       => TransactionResource::collection($this->transactions),
            'order_summary'      => $this->getOrderSummaryAttribute(),
            'previous_orders'    => PreviousOrderResource::collection($this->previous_orders),
        ];
    }
}
