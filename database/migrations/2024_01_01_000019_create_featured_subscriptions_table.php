<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->unsignedSmallInteger('duration_days')->default(30);
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_subscriptions');
    }
};
