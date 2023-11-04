<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use App\Models\Product\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @internal
 */
final class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    // for role user
    public function testIsNotAccessToChangeArticle(): void
    {
        $product = Product::factory()->create(['article' => 'mtokb1', 'name' => 'MTOK-B2/216-1KT3645-K']);

        $response = $this->put("/product/{$product->id}", [
            'article' => 'mtokb2',
            'name' => '216-1KT3645-K',
            'status' => $product->status,
            'data' => ['color' => 'black'],
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('error', 'У вас недостаточно прав для изменения article! ');
    }

    public function testSuccess(): void
    {
        $product = Product::factory()->create(['article' => 'mtokb1', 'name' => 'MTOK-B2/216-1KT3645-K']);

        $response = $this->put("/product/{$product->id}", [
            'article' => 'mtokb1',
            'name' => '216-1KT3645-K',
            'status' => $product->status,
            'data' => ['color' => 'black'],
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/product')
            ->assertSessionHas('success', 'Продукт успешно изменен!');
    }
}
