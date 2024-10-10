<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'model_name'              => 'MembershipCard',
            'card_no'                 => $this->card_no,
            'membership_card_type_id' => $this->id,
            'membership_card_type_name' => $this->membershipCardType->card_type_name,
            'shop_id'                 => $this->shop_id,
            'status'                  => $this->status,
            'created_at'              => $this->created_at->format('Y-m-d\TH:i:s'),
            'updated_at'              => $this->updated_at->format('Y-m-d\TH:i:s'),


        ];
    }
}


