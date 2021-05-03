<?php

namespace App\VendingMachine\Product\Resources;

use App\VendingMachine\Purchase\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'total' => $this->total,
            'purchases' => $this->whenLoaded('purchases', function () {
                return $this->purchases->map(function (Purchase $purchase) {
                    return [
                        'id' => $purchase->id,
                        'price' => $purchase->price,
                        'amount_paid' => $purchase->amount_paid,
                        'amount_returned' => $purchase->amount_returned,
                        'purchased_at' => [
                            'actual' => $purchase->created_at->toAtomString(),
                            'formatted' => Carbon::parse($purchase->created_at)->diffForHumans()
                        ]
                    ];
                });
            })
        ];
    }
}
