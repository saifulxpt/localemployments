<?php

namespace Tests\Feature;

use App\Models\District;
use App\Models\ProviderProfile;
use App\Models\ServiceCategory;
use App\Models\ServiceSubcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BecomeProviderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_seeker_can_access_become_provider_page(): void
    {
        $user = User::factory()->create([
            'role'           => 'seeker',
            'status'         => 'active',
            'phone_verified' => true,
        ]);

        $response = $this->actingAs($user, 'web')->get(route('seeker.become-provider'));
        $response->assertStatus(200);
        $response->assertSee('সার্ভিস প্রোভাইডার হিসেবে আবেদন করুন');
    }

    public function test_seeker_can_submit_provider_application(): void
    {
        $user = User::factory()->create([
            'role'           => 'seeker',
            'phone_verified' => true,
        ]);

        $cat = ServiceCategory::create(['name' => 'Cleaning', 'slug' => 'cleaning', 'icon' => 'wrench']);
        $sub = ServiceSubcategory::create(['category_id' => $cat->id, 'name' => 'Home Cleaning', 'slug' => 'home-cleaning']);

        $nidFront = UploadedFile::fake()->create('nid_front.jpg', 100, 'image/jpeg');
        $nidBack  = UploadedFile::fake()->create('nid_back.jpg', 100, 'image/jpeg');
        $selfie   = UploadedFile::fake()->create('selfie.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user, 'web')->post(route('seeker.become-provider.store'), [
            'subcategories'              => [$sub->id],
            'bio'                        => 'আমি অভিজ্ঞ হোম ক্লিনার।',
            'experience_years'           => 3,
            'hourly_rate_min'            => 500,
            'hourly_rate_max'            => 800,
            'full_name'                  => 'আব্দুল করিম',
            'nid_number'                 => '1995123456789',
            'dob'                        => '1995-05-10',
            'father_name'                => 'রহিম করিম',
            'mother_name'                => 'রাহেলা বেগম',
            'current_address'            => 'ঢাকা, বাংলাদেশ',
            'permanent_address'          => 'বরিশাল, বাংলাদেশ',
            'emergency_contact_name'     => 'সাইফুল ইসলাম',
            'emergency_contact_relation' => 'ভাই',
            'emergency_contact_phone'    => '01700000000',
            'nid_front'                  => $nidFront,
            'nid_back'                   => $nidBack,
            'selfie_with_nid'            => $selfie,
        ]);

        $response->assertRedirect(route('seeker.become-provider.status'));

        $this->assertDatabaseHas('provider_profiles', [
            'user_id'             => $user->id,
            'verification_status' => 'pending',
        ]);

        $doc = \App\Models\ProviderVerificationDoc::where('provider_id', $user->id)->first();
        $this->assertNotNull($doc);
        $this->assertEquals('1995123456789', $doc->nid_number);
    }

    public function test_seeker_can_view_application_status(): void
    {
        $user = User::factory()->create([
            'role'           => 'seeker',
            'phone_verified' => true,
        ]);

        ProviderProfile::create([
            'user_id'             => $user->id,
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($user, 'web')->get(route('seeker.become-provider.status'));
        $response->assertStatus(200);
        $response->assertSee('আবেদন পর্যালোচনাাধীন');
    }

    public function test_admin_can_approve_provider_application(): void
    {
        $admin = User::factory()->create([
            'role'           => 'admin',
            'phone_verified' => true,
        ]);

        $user = User::factory()->create([
            'role'           => 'seeker',
            'phone_verified' => true,
        ]);

        ProviderProfile::create([
            'user_id'             => $user->id,
            'verification_status' => 'pending',
            'is_verified'         => false,
        ]);

        $response = $this->actingAs($admin, 'web')->post(route('admin.verifications.approve', $user->id));
        $response->assertRedirect();

        $user->refresh();
        $this->assertEquals('provider', $user->role);
        $this->assertEquals('approved', $user->providerProfile->verification_status);
        $this->assertTrue((bool)$user->providerProfile->is_verified);
    }
}
