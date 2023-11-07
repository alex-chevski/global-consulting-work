<?php

declare(strict_types=1);

namespace App\Models\Product;

use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $article
 * @property string $name
 * @property string $status
 * @property array $data
 */
class Product extends Model
{
    use HasFactory;
    use Notifiable;

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

    public static function new(string $article, string $name, string $status, array $keys, array $values)
    {
        return static::create([
            'article' => $article,
            'name' => $name,
            'status' => $status,
            'data' => toArrayData($keys, $values),
        ]);
    }

    public function put(string $article, string $name, string $status, array $keys, array $values)
    {
        return DB::transaction(function () use ($article, $name, $status, $keys, $values): void {
            if ($this->userToChangeArticle($article)) {
                throw new DomainException('У вас недостаточно прав для изменения article! ');
            }

            $product = $this->updateOrFail([
                'article' => $article,
                'name' => $name,
                'status' => $status,
                'data' => toArrayData($keys, $values),
            ]);
        });
    }

    public function scopeForStatus(Builder $query, self $product)
    {
        return $query->where('status', $product->status);
    }

    public function scopeForProduct(Builder $query, string $article)
    {
        return $query->where('article', $article)->first();
    }

    public function scopeForId(Builder $query, int $id)
    {
        return static::findOrFail($id);
    }

    public static function checkAccess(): bool
    {
        return isManageArticle();
    }

    public function routeNotificationForMail($notification)
    {
        // Вернуть только адрес электронной почты ...
        return app()->make('config')->get('role')['products']['email'];
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
