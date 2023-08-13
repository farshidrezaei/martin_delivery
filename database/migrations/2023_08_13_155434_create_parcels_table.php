<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {
        Schema::create('parcels', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->uuid()->index();

            $table->foreignId('delivery_courier_id')->constrained('delivery_couriers');
            $table->foreignId('business_id')->constrained('businesses');

            $table->string('status')->index();

            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('parcels');
    }
};
