<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Product;

use App\Models\Product\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @author frostep
 * @internal
 */
final class CreateTest extends TestCase
{
    use DatabaseTransactions;

    public function testNew(): void
    {
        $product = Product::new($article = 'article', $name = 'name', $status = 'available', $keys = ['color'], $values = ['red']);

        self::assertNotEmpty($product);

        self::assertEquals($article, $product->article);
        self::assertEquals($name, $product->name);
        self::assertEquals($status, $product->status);
        self::assertIsArray($product->data);
        self::assertEquals(toArrayData($keys, $values), $product->data);
    }
}
