<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'area_id')) {
                $table->unsignedBigInteger('area_id')->nullable();
            }
            $table->foreign('district_id')->references('id')->on('districts')->nullOnDelete();
            $table->foreign('area_id')->references('id')->on('areas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropForeign(['area_id']);
        });
    }
};
