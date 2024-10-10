<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'purpose'        => $this->purpose,
            'amount'         => $this->amount,
            'date'           => Carbon::parse($this->date)->format('Y-m-d\TH:i:s'),
            'user_name'      => $this->created_by?->name,
            'created_at'     => $this->created_at->format('Y-m-d\TH:i:s'),
            'updated_at'     => $this->updated_at->format('Y-m-d\TH:i:s'),
            'status'         =>$this->status === 1 ? 'Active' : 'Inactive',
         
        ];
    }
}


