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
final class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    private $product;

    // for user role
    public function testAccessError(): void
    {
        $product = Product::new('article', 'name', 'available', ['color'], ['size']);

        $this->expectExceptionMessage('У вас недостаточно прав для изменения article! ');
        $product = $product->put(
            'article2',
            'name2',
            'status2',
            ['color'],
            ['size'],
        );
    }

    public function testSuccess(): void
    {
        $product = Product::new('article', 'name', 'available', ['color'], ['size']);

        $product = $product->put(
            $article = 'article',
            $name = 'name2',
            $status = 'status2',
            $keys = ['color'],
            $values = ['white']
        );

        self::assertEquals($article, $product->article);
        self::assertEquals($name, $product->name);
        self::assertEquals($status, $product->status);
        self::assertEquals(toArrayData($keys, $values), $product->data);
    }
}
