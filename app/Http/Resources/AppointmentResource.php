<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        $services = [];
        
        foreach($this?->services as $service){
            $services[] = $service->name;
        }
        return [
            'id'               => $this->id,
            'invoice_number'   => $this->invoice_number,
            'name'             => $this->name,
            'phone'            => $this->phone,
            'email'            => $this->email,
            'message'          => $this->message,
            'shop'             => $this?->get_shop?->name,
            'category_name'    => $this?->get_category?->name,
            'service_name'     => $services,
            'date'             => Carbon::parse($this->date)->format('Y-m-d\TH:i:s'),
            
            'created_at'       => $this->created_at->format('Y-m-d\TH:i:s'),
            'updated_at'       => $this->updated_at->format('Y-m-d\TH:i:s')


                // 'category_id'             => 'Category',
                // 'product_id'              => 'Product',
                // 'date'                    => 'Date',
                // 'shop_id'                 => 'Shop Id',


        ];
    }
}


