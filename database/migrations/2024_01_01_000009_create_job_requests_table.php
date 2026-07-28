<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seeker_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subcategory_id')->constrained('service_subcategories');
            $table->string('title', 255);
            $table->text('description');
            $table->foreignId('district_id')->constrained('districts');
            $table->foreignId('area_id')->constrained('areas');
            $table->string('address_detail', 255)->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time', 10)->nullable();
            $table->enum('flexibility', ['fixed', 'flexible', 'urgent'])->default('flexible');
            $table->json('photos')->nullable();
            $table->enum('status', ['open', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('open');
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('total_bids')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_requests');
    }
};
