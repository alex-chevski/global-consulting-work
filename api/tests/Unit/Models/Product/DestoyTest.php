<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Product;

use App\Models\Product\Product;
use App\UseCases\Products\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
        $product = Product::new($article = 'article', $name = 'name', $status = 'available', $keys = ['color'], $values = ['size']);

        self::assertEquals($article, $product->article);
        self::assertEquals($name, $product->name);
        self::assertEquals($status, $product->status);
        self::assertEquals(toArrayData($keys, $values), $product->data);

        $service = $this->createService();

        $service->remove($product->id);

        self::expectException(ModelNotFoundException::class);
        Product::findOrFail($product->id);
    }

    private function createService()
    {
        return app()->make(ProductService::class);
    }
}
