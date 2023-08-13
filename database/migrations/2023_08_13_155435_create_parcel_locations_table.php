<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {
        Schema::create('parcel_locations', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->foreignId('parcel_id')->constrained('parcels');
            $table->enum('type', ['source', 'destination'])->index();
            $table->string('name');
            $table->string('address', 2000);
            $table->string('phone', 11);
            $table->decimal('latitude', 3, 8);
            $table->decimal('longitude', 3, 8);

            $table->unique(['parcel_id', 'type']);
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('parcel_locations');
    }
};
