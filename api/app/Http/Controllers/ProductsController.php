<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product\Product;
use App\UseCases\Products\ProductService;

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

    public function index()
    {
        $product = Product::orderBy('id', 'DESC')->take(10)->get();

        return view('product.home', compact('product'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(CreateRequest $request)
    {
        $this->service->add($request);

        return redirect()->route('product.index');
    }

    public function show(Product $product)
    {
        return view('product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $statuses = Product::statusList();

        return view('product.edit', compact('product', 'statuses'));
    }

    public function update(UpdateRequest $request, Product $product)
    {
        try{
            $this->service->update($request, $product);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('product.index');
    }

    public function destroy(Product $product)
    {
        $this->service->remove($product->id);

        return redirect()->route('product.index');
    }
}
