<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insertOrIgnore([
            'key' => 'hero_image',
            'value' => 'https://images.unsplash.com/photo-1600320844754-07ed9222eb61?q=80&w=800&auto=format&fit=crop',
            'type' => 'file',
            'group' => 'general',
            'description' => 'Hero section background image (Home Page)',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('key', 'hero_image')->delete();
    }
};
