<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('parcel_track_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcel_id')->constrained('parcels');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamp('created_at');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('parcel_track_logs');
    }
};
