<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product\Product;
use App\UseCases\Products\ProductService;
use DomainException;

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
        $products = Product::orderBy('id', 'DESC')->take(10)->get();
        $statuses = Product::statusList();

        return view('products.index', compact('products', 'statuses'));
    }

    public function create()
    {
        $statuses = Product::statusList();

        return view('products.create', compact('statuses'));
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->service->add($request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('product.index')
            ->with('success', 'Создан успешно продукт! Проверьте почту!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $statuses = Product::statusList();

        return view('products.edit', compact('product', 'statuses'));
    }

    public function update(UpdateRequest $request, Product $product)
    {
        try {
            $this->service->update($request, $product);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('product.index')
            ->with('success', 'Продукт успешно изменен!');
    }

    public function destroy(Product $product)
    {
        $this->service->remove($product->id);

        return redirect()->route('product.index');
    }
}
