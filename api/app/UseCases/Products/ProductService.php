<?php

declare(strict_types=1);

namespace App\UseCases\Products;

use App\Http\Requests\Product\CreateRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Jobs\Product\CreateProduct;
use App\Models\Product\Product;

class ProductService
{
    public function add(CreateRequest $request): void
    {
        $product = Product::new(
            $request['article'],
            $request['name'],
            $request['status'],
            $request['keys'],
            $request['values'],
        );

        // job
        CreateProduct::dispatch($product);
    }

    public function update(UpdateRequest $request, Product $product): void
    {
        $product = $product->put(
            $request['article'],
            $request['name'],
            $request['status'],
            $request['keys'],
            $request['values'],
        );

        // event

        // return $product;
    }

    public function remove(int $id)
    {
        $product = Product::forId($id);
        $product->delete();

        return $product;
    }

    private function getProduct(int $id)
    {
        return Product::findOrFail($id);
    }
}
