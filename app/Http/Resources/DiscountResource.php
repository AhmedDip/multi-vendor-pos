<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'model_name'=>'Discount',
            'name'=>$this->name,
            'amount'=>$this->amount,
            'percentage'=>$this->percentage,
            'coupon_code'=>$this->coupon_code,
            'status'=>$this->status,
            'shop_id'=>$this->shop_id,
            'sort_order'=>$this->sort_order,
        ];
    }
}
