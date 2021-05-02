<?php

namespace App\VendingMachine\Machine\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|between:0,'.config('vending_machine.max_amount')
        ];
    }
}
