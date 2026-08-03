<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProviderProfile;
use App\Models\ProviderSkill;
use App\Models\ServiceSubcategory;
use App\Models\District;
use App\Models\Area;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('bn_BD'); // Use Bengali locale if possible, or english

        $districts = District::all();
        $subcategories = ServiceSubcategory::all();

        if ($subcategories->isEmpty()) {
            $this->command->info("No subcategories found. Please run ServiceCategorySeeder first.");
            return;
        }

        $names = [
            'কামরুল হাসান', 'শফিকুল ইসলাম', 'রাকিবুল হাসান', 'মোহাম্মদ আলী', 
            'তারিকুল ইসলাম', 'সুমন রেজা', 'আব্দুর রহমান', 'নাজমুল হুদা', 
            'সোহেল রানা', 'মাহমুদুল হাসান', 'জহিরুল ইসলাম', 'আরিফ হোসেন'
        ];

        foreach ($names as $index => $name) {
            $district = $districts->random();
            $area = Area::where('district_id', $district->id)->inRandomOrder()->first();

            // Create User
            $user = User::create([
                'name' => $name,
                'phone' => '017000000' . str_pad($index + 10, 2, '0', STR_PAD_LEFT),
                'password' => Hash::make('12345678'),
                'role' => 'provider',
                'district_id' => $district->id,
                'area_id' => $area ? $area->id : null,
                'avatar' => null, // Let accessor fallback to ui-avatars
            ]);

            // Create Provider Profile
            ProviderProfile::create([
                'user_id' => $user->id,
                'bio' => 'আমি দীর্ঘ ৫ বছর ধরে এই পেশায় কাজ করছি। গ্রাহকের সন্তুষ্টিই আমার মূল লক্ষ্য।',
                'experience_years' => rand(2, 10),
                'hourly_rate' => rand(300, 1000),
                'rating_avg' => mt_rand(40, 50) / 10, // 4.0 to 5.0
                'jobs_completed' => rand(10, 150),
                'is_verified' => rand(0, 1) == 1,
                'is_available' => true,
            ]);

            // Attach 2-3 random skills
            $randomSubcategories = $subcategories->random(rand(1, 3));
            foreach ($randomSubcategories as $sub) {
                ProviderSkill::create([
                    'user_id' => $user->id,
                    'category_id' => $sub->category_id,
                    'subcategory_id' => $sub->id,
                    'experience_years' => rand(1, 5),
                ]);
            }
        }
        
        $this->command->info('Dummy Providers Seeded successfully!');
    }
}
