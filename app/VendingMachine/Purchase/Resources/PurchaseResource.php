<?php

namespace App\VendingMachine\Purchase\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'amount_paid' => $this->amount_paid,
            'amount_returned' => $this->amount_returned,
            'purchased_at' => [
                'actual' => $this->created_at->toAtomString(),
                'formatted' => Carbon::parse($this->created_at)->diffForHumans()
            ],
            'product' => [
                'id' => $this->product_id,
                'name' => $this->product_name
            ]
        ];
    }
}
