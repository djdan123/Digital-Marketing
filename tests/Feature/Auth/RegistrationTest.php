<?php

namespace Tests\Feature\Auth;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $response = $this->withSession([])->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertNoContent();
    }

    public function test_media_manager_registration_creates_and_links_a_media(): void
    {
        $response = $this->withSession([])->post('/register', [
            'name' => 'Media Manager',
            'email' => 'media@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'media_manager',
            'company_name' => 'Radio Isanganiro',
        ]);

        $response->assertNoContent();

        $user = User::where('email', 'media@example.com')->firstOrFail();
        $media = Media::where('name', 'Radio Isanganiro')->first();

        $this->assertNotNull($media);
        $this->assertSame($media->id, $user->media_id);
    }
}
