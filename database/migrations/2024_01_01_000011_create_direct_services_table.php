<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('direct_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subcategory_id')->constrained('service_subcategories');
            $table->string('title', 255);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('price_type', ['fixed', 'hourly', 'starting_from'])->default('fixed');
            $table->string('estimated_duration', 100)->nullable();
            $table->json('service_areas');
            $table->json('photos')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('total_bookings')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direct_services');
    }
};
