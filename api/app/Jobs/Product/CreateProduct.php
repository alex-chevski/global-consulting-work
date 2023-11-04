<?php

declare(strict_types=1);

namespace App\Jobs\Product;

use App\Models\Product\Product;
use App\Notifications\Product\SendEmailCreatedProductNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateProduct implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $product;
    private $user;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->user = app()->make('config')->get('role')['products']['email'];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->product->notify(new SendEmailCreatedProductNotification($this->product));
    }
}
