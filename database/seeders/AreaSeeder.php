<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\District;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Dhaka' => [
                ['name' => 'Dhanmondi',     'bn_name' => 'ধানমন্ডি'],
                ['name' => 'Mirpur',        'bn_name' => 'মিরপুর'],
                ['name' => 'Gulshan',       'bn_name' => 'গুলশান'],
                ['name' => 'Motijheel',     'bn_name' => 'মতিঝিল'],
                ['name' => 'Uttara',        'bn_name' => 'উত্তরা'],
                ['name' => 'Rampura',       'bn_name' => 'রামপুরা'],
                ['name' => 'Mohammadpur',   'bn_name' => 'মোহাম্মদপুর'],
                ['name' => 'Lalbagh',       'bn_name' => 'লালবাগ'],
                ['name' => 'Tejgaon',       'bn_name' => 'তেজগাঁও'],
                ['name' => 'Khilgaon',      'bn_name' => 'খিলগাঁও'],
                ['name' => 'Demra',         'bn_name' => 'ডেমরা'],
                ['name' => 'Badda',         'bn_name' => 'বাড্ডা'],
            ],
            'Chittagong' => [
                ['name' => 'Kotwali',       'bn_name' => 'কোতোয়ালি'],
                ['name' => 'Panchlaish',    'bn_name' => 'পাঁচলাইশ'],
                ['name' => 'Halishahar',    'bn_name' => 'হালিশহর'],
                ['name' => 'Double Mooring','bn_name' => 'ডবলমুরিং'],
                ['name' => 'Chandgaon',     'bn_name' => 'চান্দগাঁও'],
                ['name' => 'Pahartali',     'bn_name' => 'পাহাড়তলী'],
                ['name' => 'Sitakund',      'bn_name' => 'সীতাকুণ্ড'],
                ['name' => 'Anwara',        'bn_name' => 'আনোয়ারা'],
            ],
            'Jashore' => [
                ['name' => 'Sadar',         'bn_name' => 'সদর'],
                ['name' => 'Kotwali',       'bn_name' => 'কোতোয়ালি'],
                ['name' => 'Manirampur',    'bn_name' => 'মণিরামপুর'],
                ['name' => 'Abhaynagar',    'bn_name' => 'অভয়নগর'],
                ['name' => 'Chaugachha',    'bn_name' => 'চৌগাছা'],
                ['name' => 'Jhikargachha',  'bn_name' => 'ঝিকরগাছা'],
                ['name' => 'Keshabpur',     'bn_name' => 'কেশবপুর'],
                ['name' => 'Sharsha',       'bn_name' => 'শার্শা'],
            ],
            'Khulna' => [
                ['name' => 'Sonadanga',     'bn_name' => 'সোনাডাঙ্গা'],
                ['name' => 'Khalishpur',    'bn_name' => 'খালিশপুর'],
                ['name' => 'Daulatpur',     'bn_name' => 'দৌলতপুর'],
                ['name' => 'Khan Jahan Ali','bn_name' => 'খান জাহান আলী'],
                ['name' => 'Batiaghata',    'bn_name' => 'বটিয়াঘাটা'],
            ],
            'Rajshahi' => [
                ['name' => 'Boalia',        'bn_name' => 'বোয়ালিয়া'],
                ['name' => 'Rajpara',       'bn_name' => 'রাজপাড়া'],
                ['name' => 'Shah Makhdum',  'bn_name' => 'শাহ মখদুম'],
                ['name' => 'Paba',          'bn_name' => 'পবা'],
                ['name' => 'Godagari',      'bn_name' => 'গোদাগাড়ী'],
            ],
            'Sylhet' => [
                ['name' => 'Sadar',         'bn_name' => 'সদর'],
                ['name' => 'South Surma',   'bn_name' => 'দক্ষিণ সুরমা'],
                ['name' => 'Beani Bazar',   'bn_name' => 'বিয়ানীবাজার'],
                ['name' => 'Golapganj',     'bn_name' => 'গোলাপগঞ্জ'],
            ],
            'Gazipur' => [
                ['name' => 'Gazipur Sadar', 'bn_name' => 'গাজীপুর সদর'],
                ['name' => 'Tongi',         'bn_name' => 'টঙ্গী'],
                ['name' => 'Kaliakair',     'bn_name' => 'কালিয়াকৈর'],
                ['name' => 'Kapasia',       'bn_name' => 'কাপাসিয়া'],
            ],
            'Narayanganj' => [
                ['name' => 'Sadar',         'bn_name' => 'সদর'],
                ['name' => 'Araihazar',     'bn_name' => 'আড়াইহাজার'],
                ['name' => 'Rupganj',       'bn_name' => 'রূপগঞ্জ'],
                ['name' => 'Sonargaon',     'bn_name' => 'সোনারগাঁও'],
            ],
            'Mymensingh' => [
                ['name' => 'Sadar',         'bn_name' => 'সদর'],
                ['name' => 'Trishal',       'bn_name' => 'ত্রিশাল'],
                ['name' => 'Bhaluka',       'bn_name' => 'ভালুকা'],
            ],
            'Barisal' => [
                ['name' => 'Sadar',         'bn_name' => 'সদর'],
                ['name' => 'Wazirpur',      'bn_name' => 'উজিরপুর'],
                ['name' => 'Muladi',        'bn_name' => 'মুলাদী'],
            ],
        ];

        foreach ($data as $districtName => $areas) {
            $district = District::where('name', $districtName)->first();
            if (!$district) continue;

            foreach ($areas as $area) {
                Area::firstOrCreate(
                    ['district_id' => $district->id, 'name' => $area['name']],
                    array_merge($area, ['district_id' => $district->id])
                );
            }
        }
    }
}
