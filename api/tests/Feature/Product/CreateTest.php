<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use App\Models\Product\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @internal
 */
final class CreateTest extends TestCase
{
    use DatabaseTransactions;

    public function testSuccess(): void
    {
        $product = Product::factory()->make();

        $response = $this->post('/product', [
            'article' => $product->article,
            'name' => 'MTOK-B2/216-1KT3645-K',
            'status' => $product->status,
            'keys' => ['color'],
            'values' => ['black'],
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/product')
            ->assertSessionHas('success', 'Создан успешно продукт! Проверьте почту!');
    }

    public function testUniqueError(): void
    {
        $product = Product::factory()->make();

        $response = $this->post('/product', [
            'article' => 'MTOK-B2/216-1KT3645-K',
            'name' => $product->name,
            'status' => $product->status,
            'keys' => ['color'],
            'values' => ['black'],
        ]);

        $product = Product::factory()->make();

        $response = $this->post('/product', [
            'article' => 'MTOK-B2/216-1KT3645-K',
            'name' => $product->name,
            'status' => $product->status,
            'keys' => ['color'],
            'values' => ['black'],
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHasErrors(['article']);
    }
}
