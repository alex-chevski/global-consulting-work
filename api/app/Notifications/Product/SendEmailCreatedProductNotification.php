<?php

declare(strict_types=1);

namespace App\Notifications\Product;

use App\Models\Product\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class SendEmailCreatedProductNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    private $product;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Продукт создан' . $this->product->article)
            ->greeting('Привет!')
            ->line('Ваш продукт' . $this->product->name . 'успешно создан! ')
            ->action('Перейти на продукт', route('product.show', $this->product))
            ->line('Спасибо что используете наше приложение!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
        ];
    }
}
