<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_google_redirects_to_socialite()
    {
        $response = $this->get('/auth/google');
        // Socialite redirects to google accounts
        $response->assertStatus(302);
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    public function test_google_callback_shows_phone_form_for_new_user()
    {
        // We simulate session data from Google
        $response = $this->withSession([
            'google_signup_data' => [
                'google_id' => '12345',
                'name'      => 'Google User',
                'email'     => 'test@gmail.com',
                'avatar'    => null,
            ]
        ])->get('/auth/google/phone');

        $response->assertStatus(200);
        $response->assertSee('ফোন নম্বর যোগ করুন');
    }

    public function test_storing_phone_completes_registration_and_sends_otp()
    {
        $this->withSession([
            'google_signup_data' => [
                'google_id' => '12345',
                'name'      => 'Google User',
                'email'     => 'test@gmail.com',
                'avatar'    => null,
            ]
        ]);

        $response = $this->post('/auth/google/phone', [
            'phone' => '01711223344',
        ]);

        // It should redirect to OTP verification page
        $response->assertRedirect(route('otp.show'));
        
        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com',
            'phone' => '01711223344',
            'google_id' => '12345',
        ]);
        
        // Assert user_id is in session
        $this->assertTrue(session()->has('otp_user_id'));
    }

    public function test_unverified_user_can_access_otp_page_and_change_phone()
    {
        $user = \App\Models\User::create([
            'name'           => 'Google User',
            'email'          => 'googleuser@gmail.com',
            'phone'          => '01711223344',
            'google_id'      => 'google-12345',
            'role'           => 'seeker',
            'phone_verified' => false,
            'otp'            => '123456',
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        $this->actingAs($user);

        // Access OTP page without redirect loop
        $response = $this->get(route('otp.show'));
        $response->assertStatus(200);
        $response->assertSee('ফোন নম্বর যাচাই');

        // Access Change Phone page
        $response = $this->get(route('otp.change-phone'));
        $response->assertStatus(200);
        $response->assertSee('ফোন নম্বর পরিবর্তন');

        // Submit new phone
        $response = $this->post(route('otp.change-phone.store'), [
            'phone' => '01899887766',
        ]);

        $response->assertRedirect(route('otp.show'));
        $user->refresh();
        $this->assertEquals('01899887766', $user->phone);
        $this->assertFalse($user->phone_verified);
    }
}
