<?php

namespace Database\Seeders;

use App\VendingMachine\Product\Models\Product;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;

class InitializeProductsSeeder extends Seeder
{
    const PRODUCTS = [
        [
            'name' => 'Coke',
            'price' => 20,
            'total' => 10
        ],
        [
            'name' => 'Pepsi',
            'price' => 25,
            'total' => 10
        ],
        [
            'name' => 'Dew',
            'price' => 30,
            'total' => 10
        ]
    ];

    /**
     * @var Product
     */
    private Product $product;
    /**
     * @var DatabaseManager
     */
    private DatabaseManager $databaseManager;

    public function __construct(Product $product, DatabaseManager $databaseManager)
    {
        $this->product = $product;
        $this->databaseManager = $databaseManager;
    }

    public function run()
    {
        collect(self::PRODUCTS)->each(function (array $product) {
            $this->product->newQuery()
                ->where('name', $product['name'])
                ->firstOrCreate(['name' => $product['name']], [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'total' => $product['total']
                ]);
        });
    }
}
