<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->string('nid_number', 20)->nullable();
            $table->string('profile_photo')->nullable();
            $table->json('portfolio_photos')->nullable();
            $table->json('availability')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected', 'not_submitted'])->default('not_submitted');
            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->unsignedInteger('total_jobs')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_until')->nullable();
            $table->decimal('hourly_rate_min', 10, 2)->nullable();
            $table->decimal('hourly_rate_max', 10, 2)->nullable();
            $table->unsignedTinyInteger('response_rate')->default(100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_profiles');
    }
};
