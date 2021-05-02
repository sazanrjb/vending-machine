<?php

namespace App\VendingMachine\Machine\Actions;

use App\VendingMachine\Machine\DTOs\PurchaseProductDTO;
use App\VendingMachine\Machine\Manager as MachineManager;
use App\VendingMachine\Machine\Requests\PurchaseProductRequest;
use App\VendingMachine\Product\Manager as ProductManager;
use Illuminate\Http\Response;

class PurchaseAction
{
    /**
     * @param PurchaseProductRequest $request
     * @param MachineManager $machineManager
     * @param ProductManager $productManager
     * @return Response
     * @throws \Throwable
     */
    public function __invoke(PurchaseProductRequest $request, MachineManager $machineManager, ProductManager $productManager)
    {
        $product = $productManager->find($request->input('product_id'));

        $machineManager->purchaseProduct(new PurchaseProductDTO($product, $request->input('amount')));

        return new Response([
            'message' => trans('general.product_purchased')
        ]);
    }
}
