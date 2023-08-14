<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\DeliveryCourier;
use Illuminate\Console\Command;

class GenerateTestTokensCommand extends Command
{
    protected $signature = 'generate:test-tokens';

    protected $description = 'Command description';

    public function handle(): void
    {
        $delivery = DeliveryCourier::factory()->create();
        $deliveryToken = $delivery->createToken('delivery', ['delivery'])->plainTextToken;

        $business = Business::factory()->create();
        $businessToken = $business->createToken('business', ['business'])->plainTextToken;

        $this->warn('Business Token: ');
        $this->info($businessToken);

        $this->warn('Delivery Token: ');
        $this->info($deliveryToken);

    }
}
