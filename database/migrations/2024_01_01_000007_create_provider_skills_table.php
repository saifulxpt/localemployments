<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subcategory_id')->constrained('service_subcategories')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['provider_id', 'subcategory_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_skills');
    }
};
