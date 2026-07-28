<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use App\Models\ServiceSubcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Home Cleaning', 'icon' => 'sparkles', 'sort_order' => 1,
                'subcategories' => ['Deep Cleaning', 'Daily Cleaning', 'After-Party Cleaning', 'Sofa Cleaning', 'Kitchen Cleaning'],
            ],
            [
                'name' => 'Plumbing', 'icon' => 'wrench', 'sort_order' => 2,
                'subcategories' => ['Pipe Fitting', 'Drain Cleaning', 'Water Tank Install', 'Tap Repair', 'Bathroom Fitting'],
            ],
            [
                'name' => 'Electrical Work', 'icon' => 'bolt', 'sort_order' => 3,
                'subcategories' => ['House Wiring', 'Fan & AC Install', 'Switch Repair', 'Generator Setup', 'Solar Panel'],
            ],
            [
                'name' => 'AC Servicing', 'icon' => 'sun', 'sort_order' => 4,
                'subcategories' => ['AC Deep Cleaning', 'Gas Refill', 'AC Repair', 'AC Install', 'AC Uninstall'],
            ],
            [
                'name' => 'Painting', 'icon' => 'paint-brush', 'sort_order' => 5,
                'subcategories' => ['Interior Painting', 'Exterior Painting', 'Wood Polish', 'Wall Putty', 'Waterproofing'],
            ],
            [
                'name' => 'Carpentry', 'icon' => 'scissors', 'sort_order' => 6,
                'subcategories' => ['Door & Window Repair', 'Furniture Repair', 'Cabinet Install', 'Custom Furniture', 'Ceiling Work'],
            ],
            [
                'name' => 'Home Cooking', 'icon' => 'fire', 'sort_order' => 7,
                'subcategories' => ['Daily Cooking', 'Party Cooking', 'Tiffin Service', 'Cake & Pastry', 'Special Occasion'],
            ],
            [
                'name' => 'Babysitting', 'icon' => 'heart', 'sort_order' => 8,
                'subcategories' => ['Full-Time Nanny', 'Part-Time Babysitter', 'Night Duty', 'Special Needs Care', 'Elderly Care'],
            ],
            [
                'name' => 'Home Tutoring', 'icon' => 'academic-cap', 'sort_order' => 9,
                'subcategories' => ['School Subjects', 'English Language', 'Mathematics', 'Science', 'Quran & Arabic'],
            ],
            [
                'name' => 'Beauty & Salon', 'icon' => 'sparkles', 'sort_order' => 10,
                'subcategories' => ['Bridal Makeup', 'Facial & Skincare', 'Hair Cut & Style', 'Manicure & Pedicure', 'Mehendi'],
            ],
            [
                'name' => 'Pest Control', 'icon' => 'bug-ant', 'sort_order' => 11,
                'subcategories' => ['Cockroach Control', 'Rat Control', 'Termite Treatment', 'Mosquito Spray', 'Bed Bug Treatment'],
            ],
            [
                'name' => 'Laundry', 'icon' => 'cloud', 'sort_order' => 12,
                'subcategories' => ['Wash & Fold', 'Dry Cleaning', 'Ironing Only', 'Curtain Washing', 'Blanket & Carpet'],
            ],
            [
                'name' => 'Driver Service', 'icon' => 'truck', 'sort_order' => 13,
                'subcategories' => ['Daily Driver', 'Trip Driver', 'Airport Pickup', 'Night Driver', 'Monthly Driver'],
            ],
            [
                'name' => 'IT & Repair', 'icon' => 'computer-desktop', 'sort_order' => 14,
                'subcategories' => ['PC Repair', 'Printer Repair', 'CCTV Install', 'Networking', 'Phone Repair'],
            ],
            [
                'name' => 'Photography', 'icon' => 'camera', 'sort_order' => 15,
                'subcategories' => ['Wedding Photography', 'Product Photography', 'Event Coverage', 'Portrait Session', 'Videography'],
            ],
            [
                'name' => 'Event Help', 'icon' => 'calendar', 'sort_order' => 16,
                'subcategories' => ['Event Decoration', 'Catering Assistance', 'Event Management', 'Stage Setup', 'Sound & Light'],
            ],
            [
                'name' => 'Gardening', 'icon' => 'leaf', 'sort_order' => 17,
                'subcategories' => ['Garden Setup', 'Plant Trimming', 'Plant Care', 'Rooftop Garden', 'Lawn Mowing'],
            ],
            [
                'name' => 'Shifting & Moving', 'icon' => 'truck', 'sort_order' => 18,
                'subcategories' => ['Furniture Shifting', 'Packing & Unpacking', 'Loading & Unloading', 'Office Moving', 'Pickup Rental'],
            ],
            [
                'name' => 'Tailoring', 'icon' => 'scissors', 'sort_order' => 19,
                'subcategories' => ['Dress Stitching', 'Alteration', 'Saree Blouse', 'School Uniform', 'Suit & Jacket'],
            ],
            [
                'name' => 'Security Guard', 'icon' => 'shield-check', 'sort_order' => 20,
                'subcategories' => ['Day Guard', 'Night Guard', 'Event Security', 'Building Security', 'VIP Security'],
            ],
        ];

        foreach ($categories as $catData) {
            $subcategories = $catData['subcategories'];
            unset($catData['subcategories']);

            $catData['slug'] = Str::slug($catData['name']);

            $category = ServiceCategory::firstOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );

            foreach ($subcategories as $index => $subName) {
                ServiceSubcategory::firstOrCreate(
                    ['slug' => Str::slug($catData['name'] . '-' . $subName)],
                    [
                        'category_id' => $category->id,
                        'name'        => $subName,
                        'slug'        => Str::slug($catData['name'] . '-' . $subName),
                        'sort_order'  => $index + 1,
                    ]
                );
            }
        }
    }
}
