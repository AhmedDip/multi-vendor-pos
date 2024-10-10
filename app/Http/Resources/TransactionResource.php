<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
          'order_id'            => $this->order_id,
          'payment_method_id'   => $this->payment_method_id,
          'payment_method_name' => $this->payment_method?->name,
          'sender_account'      => $this->sender_account,
          'trx_id'              => $this->trx_id,
          'appoinment_id'       => $this->appoinment_id,
          'amount'              => $this->amount,
          'payment_status'      => $this->payment_status,
          'payment_status_text' => \App\Models\Order::PAYMENT_STATUS_LIST[$this->payment_status],
        ];
    }
}
