<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('seeker_id')->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 5)->default('BDT');
            $table->string('payment_method', 50)->nullable();
            $table->string('gateway', 20)->default('sslcommerz');
            $table->string('transaction_id', 100)->nullable()->unique();
            $table->string('val_id', 100)->nullable();
            $table->string('session_key', 100)->nullable();
            $table->json('gateway_response')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->string('refund_ref', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
