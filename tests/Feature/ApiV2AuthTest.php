<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiV2AuthTest extends TestCase
{
    public function test_merchant_login_and_me(): void
    {
        $user = User::query()->where('email', 'demo@cnxretail.com')->first();
        if ($user === null) {
            $this->markTestSkipped('Demo merchant user not seeded.');
        }

        $login = $this->postJson('/api/v2/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        if ($login->status() !== 200) {
            $login = $this->postJson('/api/v2/auth/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);
        }

        $login->assertOk()->assertJsonPath('success', true);
        $token = $login->json('data.token');
        $this->assertNotEmpty($token);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v2/auth/me')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_admin_login_and_tenants(): void
    {
        $admin = AdminUser::query()->first();
        if ($admin === null) {
            $this->markTestSkipped('No admin user seeded.');
        }

        $plain = 'v2-test-'.uniqid();
        $admin->password = Hash::make($plain);
        $admin->save();

        $login = $this->postJson('/api/v2/admin/auth/login', [
            'email' => $admin->email,
            'password' => $plain,
        ]);

        $login->assertOk()->assertJsonPath('success', true);
        $token = $login->json('data.token');
        $this->assertNotEmpty($token);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v2/admin/tenants')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => ['tenants', 'stats'],
                'meta' => ['current_page', 'total', 'from'],
            ]);
    }

    public function test_merchant_token_cannot_access_admin(): void
    {
        $user = User::query()->where('email', 'demo@cnxretail.com')->first();
        if ($user === null) {
            $this->markTestSkipped('Demo merchant user not seeded.');
        }

        $token = auth('api')->login($user);

        // Merchant JWT cannot authenticate the admin_api guard (401) or fails typ check (403).
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v2/admin/dashboard')
            ->assertStatus(401);
    }
}
