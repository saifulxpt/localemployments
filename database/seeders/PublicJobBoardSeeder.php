<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobRequest;
use App\Models\ServiceCategory;
use App\Models\ServiceSubcategory;
use App\Models\District;
use App\Models\Area;
use App\Models\User;

class PublicJobBoardSeeder extends Seeder
{
    public function run(): void
    {
        // Get first available seeker/user
        $seeker = User::first();
        if (!$seeker) {
            $this->command->error('No user found! Please run the main seeder first.');
            return;
        }

        // Get first subcategory
        $subcategory = ServiceSubcategory::where('is_active', true)->first();
        if (!$subcategory) {
            $this->command->error('No subcategory found! Please seed categories first.');
            return;
        }

        // Get first district and area
        $district = District::where('is_active', true)->first();
        $area = Area::where('district_id', $district?->id)->first();

        if (!$district) {
            $this->command->error('No district found!');
            return;
        }

        $demoJobs = [
            [
                'title'       => 'আমার বাসার এসি মেরামত করা লাগবে',
                'description' => 'এসিতে প্রচুর শব্দ হচ্ছে এবং ঠিকমতো ঠান্ডা হচ্ছে না। দক্ষ মেকানিক প্রয়োজন।',
                'budget_min'  => 400,
                'budget_max'  => 600,
                'flexibility' => 'fixed',
                'preferred_time' => 'afternoon',
            ],
            [
                'title'       => 'বাসা পরিষ্কার করার জন্য কাউকে দরকার',
                'description' => '৩ বেডরুমের ফ্ল্যাট ডীপ ক্লিনিং করতে হবে। সব সরঞ্জাম আমাদের কাছে আছে।',
                'budget_min'  => 500,
                'budget_max'  => 800,
                'flexibility' => 'flexible',
                'preferred_time' => 'morning',
            ],
            [
                'title'       => 'রান্নাঘরের সিঙ্ক লিক হচ্ছে, প্লাম্বার দরকার',
                'description' => 'রান্নাঘরের পাইপ থেকে পানি পড়ছে। যত দ্রুত সম্ভব সমাধান দরকার।',
                'budget_min'  => 200,
                'budget_max'  => 400,
                'flexibility' => 'urgent',
                'preferred_time' => 'morning',
            ],
            [
                'title'       => 'ইন্টেরিয়র পেইন্টিং করতে হবে',
                'description' => '২ রুমের দেয়াল নতুন রং করতে হবে। রং আমি দেব, শুধু শ্রম দরকার।',
                'budget_min'  => 1000,
                'budget_max'  => 1500,
                'flexibility' => 'flexible',
                'preferred_time' => 'afternoon',
            ],
            [
                'title'       => 'গৃহশিক্ষক দরকার — ক্লাস ৬-৮',
                'description' => 'আমার সন্তানকে গণিত ও ইংরেজি পড়ানোর জন্য গৃহশিক্ষক দরকার। সপ্তাহে ৩ দিন।',
                'budget_min'  => 2000,
                'budget_max'  => 3000,
                'flexibility' => 'fixed',
                'preferred_time' => 'evening',
            ],
        ];

        foreach ($demoJobs as $jobData) {
            // Check if a similar job already exists
            if (JobRequest::where('title', $jobData['title'])->exists()) {
                $this->command->info('Skipping: ' . $jobData['title'] . ' (already exists)');
                continue;
            }

            JobRequest::create([
                'seeker_id'      => $seeker->id,
                'subcategory_id' => $subcategory->id,
                'title'          => $jobData['title'],
                'description'    => $jobData['description'],
                'district_id'    => $district->id,
                'area_id'        => $area?->id,
                'address_detail' => 'ধানমন্ডি',
                'budget_min'     => $jobData['budget_min'],
                'budget_max'     => $jobData['budget_max'],
                'preferred_date' => now()->addDays(2),
                'preferred_time' => $jobData['preferred_time'],
                'flexibility'    => $jobData['flexibility'],
                'status'         => 'open',
                'expires_at'     => now()->addDays(7),
                'total_bids'     => 0,
            ]);

            $this->command->info('Created job: ' . $jobData['title']);
        }

        $this->command->info('✅ Demo jobs seeded successfully! Total jobs: ' . JobRequest::count());
    }
}
