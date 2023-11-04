<?php

declare(strict_types=1);

namespace App\Models\Product;

use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * @property int $id
 * @property string $article
 * @property string $name
 * @property string $status
 * @property array $data
 */
class Product extends Model
{
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_UNAVAILABLE = 'unavailable';

    protected $table = 'products';

    protected $fillable = ['article', 'name', 'status', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public static function statusList(): array
    {
        return [
            self::STATUS_AVAILABLE => 'available',
            self::STATUS_UNAVAILABLE => 'unavailable',
        ];
    }

    public static function new(string $article, string $name, string $status, array $data)
    {
        return static::create([
            'article' => $article,
            'name' => $name,
            'status' => $status,
            'data' => $data,
        ]);
    }

    public function put(string $article, string $name, string $status, array $data)
    {
        return DB::transaction(function () use ($article, $name, $status, $data) {
            if ($this->userToChangeArticle($article)) {
                throw new DomainException('У вас недостаточно прав для изменения article! ');
            }

            $product = $this->make([
                'article' => $article,
                'name' => $name,
                'status' => $status,
                'data' => $data,
            ]);

            $product->update();
            return $product;
        });
    }

    public function scopeForStatus(Builder $query, self $product)
    {
        return $query->where('status', $product->status);
    }

    public static function checkAccess(): bool
    {
        return Gate::allows('manage-article');
    }

    private function userToChangeArticle(string $article)
    {
        return !static::checkAccess() && $this->checkToChangeArticle($article);
    }

    private function checkToChangeArticle(string $article): bool
    {
        return $this->article !== $article;
    }
}
