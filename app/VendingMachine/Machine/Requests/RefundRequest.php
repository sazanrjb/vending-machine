<?php

namespace App\VendingMachine\Machine\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'purchase_id' => 'required|exists:purchases,id'
        ];
    }
}
