<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'model_name'          => 'Customer',
            'name'                => $this->name,
            'phone'               => $this->phone,
            'address'             => $this->address,
            'shop_id'             => $this->shop_id,
            'status'              => $this->status,
            'membership_card_id'  => $this?->membership_card_id,
            'card_number'         => $this?->membershipCardNo?->card_no ?? null,
            'discount_percentage' => $this?->getDiscount() ?? 0,
            'created_at'          => $this->created_at->format('Y-m-d\TH:i:s'),
            'updated_at'          => $this->updated_at->format('Y-m-d\TH:i:s'),

          
        ];
    }
}


