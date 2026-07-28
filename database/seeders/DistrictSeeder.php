<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            ['name' => 'Dhaka',          'bn_name' => 'ঢাকা'],
            ['name' => 'Faridpur',       'bn_name' => 'ফরিদপুর'],
            ['name' => 'Gazipur',        'bn_name' => 'গাজীপুর'],
            ['name' => 'Gopalganj',      'bn_name' => 'গোপালগঞ্জ'],
            ['name' => 'Kishoreganj',    'bn_name' => 'কিশোরগঞ্জ'],
            ['name' => 'Madaripur',      'bn_name' => 'মাদারীপুর'],
            ['name' => 'Manikganj',      'bn_name' => 'মানিকগঞ্জ'],
            ['name' => 'Munshiganj',     'bn_name' => 'মুন্সিগঞ্জ'],
            ['name' => 'Narayanganj',    'bn_name' => 'নারায়ণগঞ্জ'],
            ['name' => 'Narsingdi',      'bn_name' => 'নরসিংদী'],
            ['name' => 'Rajbari',        'bn_name' => 'রাজবাড়ী'],
            ['name' => 'Shariatpur',     'bn_name' => 'শরীয়তপুর'],
            ['name' => 'Tangail',        'bn_name' => 'টাঙ্গাইল'],
            ['name' => 'Jashore',        'bn_name' => 'যশোর'],
            ['name' => 'Satkhira',       'bn_name' => 'সাতক্ষীরা'],
            ['name' => 'Khulna',         'bn_name' => 'খুলনা'],
            ['name' => 'Bagerhat',       'bn_name' => 'বাগেরহাট'],
            ['name' => 'Chuadanga',      'bn_name' => 'চুয়াডাঙ্গা'],
            ['name' => 'Jhenaidah',      'bn_name' => 'ঝিনাইদহ'],
            ['name' => 'Kushtia',        'bn_name' => 'কুষ্টিয়া'],
            ['name' => 'Magura',         'bn_name' => 'মাগুরা'],
            ['name' => 'Meherpur',       'bn_name' => 'মেহেরপুর'],
            ['name' => 'Narail',         'bn_name' => 'নড়াইল'],
            ['name' => 'Rajshahi',       'bn_name' => 'রাজশাহী'],
            ['name' => 'Bogura',         'bn_name' => 'বগুড়া'],
            ['name' => 'Chapainawabganj','bn_name' => 'চাঁপাইনবাবগঞ্জ'],
            ['name' => 'Joypurhat',      'bn_name' => 'জয়পুরহাট'],
            ['name' => 'Naogaon',        'bn_name' => 'নওগাঁ'],
            ['name' => 'Natore',         'bn_name' => 'নাটোর'],
            ['name' => 'Pabna',          'bn_name' => 'পাবনা'],
            ['name' => 'Sirajganj',      'bn_name' => 'সিরাজগঞ্জ'],
            ['name' => 'Rangpur',        'bn_name' => 'রংপুর'],
            ['name' => 'Dinajpur',       'bn_name' => 'দিনাজপুর'],
            ['name' => 'Gaibandha',      'bn_name' => 'গাইবান্ধা'],
            ['name' => 'Kurigram',       'bn_name' => 'কুড়িগ্রাম'],
            ['name' => 'Lalmonirhat',    'bn_name' => 'লালমনিরহাট'],
            ['name' => 'Nilphamari',     'bn_name' => 'নীলফামারী'],
            ['name' => 'Panchagarh',     'bn_name' => 'পঞ্চগড়'],
            ['name' => 'Thakurgaon',     'bn_name' => 'ঠাকুরগাঁও'],
            ['name' => 'Mymensingh',     'bn_name' => 'ময়মনসিংহ'],
            ['name' => 'Jamalpur',       'bn_name' => 'জামালপুর'],
            ['name' => 'Netrokona',      'bn_name' => 'নেত্রকোণা'],
            ['name' => 'Sherpur',        'bn_name' => 'শেরপুর'],
            ['name' => 'Sylhet',         'bn_name' => 'সিলেট'],
            ['name' => 'Habiganj',       'bn_name' => 'হবিগঞ্জ'],
            ['name' => 'Moulvibazar',    'bn_name' => 'মৌলভীবাজার'],
            ['name' => 'Sunamganj',      'bn_name' => 'সুনামগঞ্জ'],
            ['name' => 'Chittagong',     'bn_name' => 'চট্টগ্রাম'],
            ['name' => 'Bandarban',      'bn_name' => 'বান্দরবান'],
            ['name' => 'Brahmanbaria',   'bn_name' => 'ব্রাহ্মণবাড়িয়া'],
            ['name' => 'Chandpur',       'bn_name' => 'চাঁদপুর'],
            ['name' => 'Comilla',        'bn_name' => 'কুমিল্লা'],
            ['name' => "Cox's Bazar",    'bn_name' => "কক্সবাজার"],
            ['name' => 'Feni',           'bn_name' => 'ফেনী'],
            ['name' => 'Khagrachhari',   'bn_name' => 'খাগড়াছড়ি'],
            ['name' => 'Lakshmipur',     'bn_name' => 'লক্ষ্মীপুর'],
            ['name' => 'Noakhali',       'bn_name' => 'নোয়াখালী'],
            ['name' => 'Rangamati',      'bn_name' => 'রাঙামাটি'],
            ['name' => 'Barisal',        'bn_name' => 'বরিশাল'],
            ['name' => 'Barguna',        'bn_name' => 'বরগুনা'],
            ['name' => 'Bhola',          'bn_name' => 'ভোলা'],
            ['name' => 'Jhalokati',      'bn_name' => 'ঝালকাঠি'],
            ['name' => 'Patuakhali',     'bn_name' => 'পটুয়াখালী'],
            ['name' => 'Pirojpur',       'bn_name' => 'পিরোজপুর'],
        ];

        foreach ($districts as $district) {
            District::firstOrCreate(['name' => $district['name']], $district);
        }
    }
}
