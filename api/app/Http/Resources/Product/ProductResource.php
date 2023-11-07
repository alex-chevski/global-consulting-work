<?php

declare(strict_types=1);

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $article
 * @property string $name
 * @property string $status
 * @property string $data
 */
class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'article' => $this->article,
            'name' => $this->name,
            'status' => $this->status,
            'data' => $this->data,
        ];
    }
}
