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
        $product = Product::new('article', 'name', 'available', $this->getData());

        $this->expectExceptionMessage('У вас недостаточно прав для изменения article! ');
        $product = $product->put(
            'article2',
            'name2',
            'status2',
            ['color' => 'white', 'size' => 'M'],
        );
    }

    public function testSuccess(): void
    {
        $product = Product::new('article', 'name', 'available', $this->getData());

        $product = $product->put(
            $article = 'article',
            $name = 'name2',
            $status = 'status2',
            $data = ['color' => 'white', 'size' => 'M'],
        );

        self::assertEquals($article, $product->article);
        self::assertEquals($name, $product->name);
        self::assertEquals($status, $product->status);
        self::assertEquals($data, $product->data);
    }

    private function getData()
    {
        return [
            'color' => 'black',
            'size' => 'L',
        ];
    }
}
