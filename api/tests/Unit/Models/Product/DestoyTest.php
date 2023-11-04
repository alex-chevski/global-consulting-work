<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Product;

use App\Models\Product\Product;
use App\UseCases\Products\ProductService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

/**
 * @author frostep
 * @internal
 */
final class DestoyTest extends TestCase
{
    use DatabaseTransactions;

    // for user role
    public function testSuccess(): void
    {
        $product = Product::new($article = 'article', $name = 'name', $status = 'available', $data = $this->getData());

        self::assertEquals($article, $product->article);
        self::assertEquals($name, $product->name);
        self::assertEquals($status, $product->status);
        self::assertEquals($data, $product->data);

        $service = $this->createService();

        Product::findOrFail($product->id);

        $service->remove($product->id);

        self::expectException(ModelNotFoundException::class);
        Product::findOrFail($product->id);
    }

    private function createService()
    {
        return app()->make(ProductService::class);
    }

    private function getData()
    {
        return [
            'color' => 'black',
            'size' => 'L',
        ];
    }
}
