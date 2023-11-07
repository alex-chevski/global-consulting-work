<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product\Product;
use App\UseCases\Products\ProductService;
use DomainException;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    private $service;

    /**
     * @param ProductsService
     */
    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->service->add($request);
        } catch (DomainException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new ProductResource(Product::forProduct($request['article']));
    }

    public function update(UpdateRequest $request, Product $product)
    {
        $this->checkUniqueArticleRequest($request, $product);

        try {
            $this->service->update($request, $product);
        } catch (DomainException $e) {
            return response()->json(['error', $e->getMessage()], Response::HTTP_FORBIDDEN);
        }

        return new ProductResource(Product::forId($product->id));
    }

    public function destroy(Product $product)
    {
        $this->service->remove($product->id);

        return new ProductResource($product);
    }

    // в UpdateRequest не получится вытащить $product->id
    private function checkUniqueArticleRequest($request, $product): void
    {
        $request->validate(['article' => "required|string|regex:/^[a-z0-9]+$/i|unique:products,article,{$product->id},id"]);
    }
}
