<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_is_accessible(): void
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertSee('নতুন একাউন্ট');
    }

    public function test_user_can_register_successfully(): void
    {
        $response = $this->post(route('register.store'), [
            'name'                  => 'Test Seeker',
            'phone'                 => '01712345678',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('otp.show'));
        $response->assertSessionHas('otp_user_id');

        $this->assertDatabaseHas('users', [
            'name'  => 'Test Seeker',
            'phone' => '01712345678',
            'role'  => 'seeker',
            'phone_verified' => false,
        ]);

        $user = User::where('phone', '01712345678')->first();
        $this->assertNotNull($user->otp);
        $this->assertNotNull($user->otp_expires_at);
    }

    public function test_registration_validation_fails_for_duplicate_phone(): void
    {
        User::factory()->create([
            'phone' => '01712345678',
        ]);

        $response = $this->post(route('register.store'), [
            'name'                  => 'Another User',
            'phone'                 => '01712345678',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'seeker',
        ]);

        $response->assertSessionHasErrors(['phone']);
    }

    public function test_user_can_verify_otp_and_login(): void
    {
        $user = User::create([
            'name'           => 'OTP Test User',
            'phone'          => '01812345678',
            'password'       => Hash::make('password123'),
            'role'           => 'seeker',
            'otp'            => '123456',
            'otp_expires_at' => now()->addMinutes(5),
            'phone_verified' => false,
        ]);

        $response = $this->withSession(['otp_user_id' => $user->id])
            ->post(route('otp.verify'), [
                'otp' => '123456',
            ]);

        $response->assertRedirect(route('seeker.dashboard'));
        $this->assertAuthenticatedAs($user);

        $user->refresh();
        $this->assertTrue($user->phone_verified);
        $this->assertNull($user->otp);
    }

    public function test_invalid_otp_fails(): void
    {
        $user = User::create([
            'name'           => 'Invalid OTP User',
            'phone'          => '01912345678',
            'password'       => Hash::make('password123'),
            'role'           => 'seeker',
            'otp'            => '123456',
            'otp_expires_at' => now()->addMinutes(5),
            'phone_verified' => false,
        ]);

        $response = $this->withSession(['otp_user_id' => $user->id])
            ->post(route('otp.verify'), [
                'otp' => '654321',
            ]);

        $response->assertSessionHasErrors(['otp']);
        $this->assertGuest();
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('ফোন নম্বর');
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::create([
            'name'           => 'Verified User',
            'phone'          => '01612345678',
            'password'       => Hash::make('password123'),
            'role'           => 'seeker',
            'phone_verified' => true,
        ]);

        $response = $this->post(route('login.attempt'), [
            'phone'    => '01612345678',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('seeker.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_incorrect_password_fails(): void
    {
        User::create([
            'name'           => 'User',
            'phone'          => '01612345678',
            'password'       => Hash::make('password123'),
            'role'           => 'seeker',
            'phone_verified' => true,
        ]);

        $response = $this->post(route('login.attempt'), [
            'phone'    => '01612345678',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['phone']);
        $this->assertGuest();
    }

    public function test_unverified_user_login_redirects_to_otp(): void
    {
        $user = User::create([
            'name'           => 'Unverified User',
            'phone'          => '01512345678',
            'password'       => Hash::make('password123'),
            'role'           => 'provider',
            'phone_verified' => false,
        ]);

        $response = $this->post(route('login.attempt'), [
            'phone'    => '01512345678',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('otp.show'));
        $this->assertEquals($user->id, session('otp_user_id'));
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::create([
            'name'           => 'User To Logout',
            'phone'          => '01312345678',
            'password'       => Hash::make('password123'),
            'role'           => 'seeker',
            'phone_verified' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('logout'));
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
