<?php

namespace App\Notifications;

use App\Http\Resources\ParcelResource;
use App\Models\Business;
use App\Models\Parcel;
use App\Notifications\Channels\WebhookChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BusinessParcelNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public readonly Parcel $parcel)
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(Business $notifiable): array
    {
        return [WebhookChannel::class];
    }

    public function toWebhook()
    {
        return json_decode(ParcelResource::make($this->parcel)->toJson(), true);
    }

    public function viaQueues(): array
    {
        return [
            WebhookChannel::class => 'webhook-queue'
        ];
    }
}
