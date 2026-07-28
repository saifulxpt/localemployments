<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->enum('booking_type', ['job_request', 'direct']);
            $table->foreignId('job_request_id')->nullable()->constrained('job_requests')->nullOnDelete();
            $table->foreignId('bid_id')->nullable()->constrained('job_bids')->nullOnDelete();
            $table->foreignId('direct_service_id')->nullable()->constrained('direct_services')->nullOnDelete();
            $table->foreignId('seeker_id')->constrained('users');
            $table->foreignId('provider_id')->constrained('users');
            $table->string('booking_ref', 20)->unique();
            $table->date('service_date');
            $table->string('service_time', 10)->nullable();
            $table->text('location_detail')->nullable();
            $table->decimal('service_amount', 10, 2);
            $table->decimal('platform_fee', 10, 2);
            $table->decimal('provider_earning', 10, 2);
            $table->text('seeker_note')->nullable();
            $table->text('provider_note')->nullable();
            $table->enum('status', [
                'pending', 'confirmed', 'in_progress',
                'completed', 'disputed', 'cancelled', 'refunded'
            ])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
