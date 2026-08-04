<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('provider_verification_docs', function (Blueprint $table) {
            $table->string('nid_number')->nullable()->after('provider_id');
            $table->date('dob')->nullable()->after('nid_number');
            $table->string('full_name')->nullable()->after('dob');
            $table->string('father_name')->nullable()->after('full_name');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->text('current_address')->nullable()->after('mother_name');
            $table->text('permanent_address')->nullable()->after('current_address');
            $table->string('emergency_contact_name')->nullable()->after('permanent_address');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_relation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_verification_docs', function (Blueprint $table) {
            //
        });
    }
};
