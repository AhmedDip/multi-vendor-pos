<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderTransactionDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {

        return [
            'id'              => $this->id,
            'customer_id'     => $this->customer_id,
            'customer_name'   => $this?->customer?->name,
            'customer_phone'  => $this->customer_phone,
            'order_date'      => $this->order_date,
            'total_amount'    => $this->total_amount,
            'discount_amount' => $this->discount_amount,
            'note'            => $this->note,
            'transaction'     => new TransactionResource($this->transaction),
        ];
    }
}
