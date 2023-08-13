<?php

namespace App\Notifications\Channels;

use App\Models\Business;
use App\Notifications\BusinessParcelNotification;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class WebhookChannel
{
    /**
     * @throws RequestException
     */
    public function send(Business $notifiable, BusinessParcelNotification $notification): void
    {
        Http::post($notifiable->routeNotificationForWebhook(), $notification->toWebhook())->throw();
    }
}
