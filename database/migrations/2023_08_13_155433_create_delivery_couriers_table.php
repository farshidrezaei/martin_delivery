<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('delivery_couriers', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('phone',11);
            $table->string('full_name');
            $table->decimal('lat',10,8);
            $table->decimal('long',11,8);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('delivery_couriers');
    }
};
