<?php

namespace App\VendingMachine\Machine\Actions;

use App\VendingMachine\Machine\Manager;
use App\VendingMachine\Machine\Requests\RefundRequest;
use App\VendingMachine\Purchase\Manager as PurchaseManager;
use Illuminate\Http\Response;

class RefundAction
{
    /**
     * @param RefundRequest $request
     * @param Manager $manager
     * @param PurchaseManager $purchaseManager
     * @return Response
     * @throws \Throwable
     */
    public function __invoke(RefundRequest $request, Manager $manager, PurchaseManager $purchaseManager)
    {
        $purchase = $purchaseManager->find($request->input('purchase_id'));
        $manager->refundProduct($purchase);

        return new Response([
            'message' => trans('general.product_refunded')
        ]);
    }
}
