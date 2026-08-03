<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_redirected_to_admin_dashboard_after_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $token = csrf_token();

        $response = $this->withSession(['_token' => $token])
            ->post('/login', [
                'email' => 'test@example.com',
                'password' => 'password123',
                '_token' => $token,
            ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
    }
}
