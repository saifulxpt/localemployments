<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            ['key' => 'site_logo', 'value' => '', 'type' => 'file', 'group' => 'general', 'description' => 'ওয়েবসাইটের মেইন লোগো (PNG/JPG)'],
            ['key' => 'contact_phone', 'value' => '+880 1700 000000', 'type' => 'string', 'group' => 'contact', 'description' => 'হেডার এবং ফুটারে দেখানোর জন্য ফোন নাম্বার'],
            ['key' => 'contact_email', 'value' => 'info@localemployments.com', 'type' => 'string', 'group' => 'contact', 'description' => 'যোগাযোগের ইমেইল অ্যাড্রেস'],
            ['key' => 'contact_address', 'value' => 'ঢাকা, বাংলাদেশ', 'type' => 'textarea', 'group' => 'contact', 'description' => 'অফিস বা যোগাযোগের ঠিকানা'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['site_logo', 'contact_phone', 'contact_email', 'contact_address'])->delete();
    }
};
