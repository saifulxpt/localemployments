<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_request_id')->constrained('job_requests')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('bid_amount', 10, 2);
            $table->text('message');
            $table->unsignedTinyInteger('estimated_hours')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->boolean('is_highlighted')->default(false);
            $table->timestamps();
            $table->unique(['job_request_id', 'provider_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_bids');
    }
};
