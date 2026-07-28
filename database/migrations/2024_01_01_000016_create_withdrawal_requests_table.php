<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['bkash', 'nagad', 'bank'])->default('bkash');
            $table->string('account_number', 50);
            $table->string('account_name', 150);
            $table->string('bank_name', 150)->nullable();
            $table->string('branch_name', 150)->nullable();
            $table->string('routing_number', 50)->nullable();
            $table->enum('status', ['pending', 'processing', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
