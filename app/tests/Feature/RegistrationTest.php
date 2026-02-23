<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private const PATH_REGISTER = '/register';

    private const PATH_DASHBOARD = '/dashboard';

    #[TestDox('Registration page loads')]
    public function test_registration_page_loads(): void
    {
        $response = $this->get(self::PATH_REGISTER);

        $response->assertStatus(200);
        $response->assertSee('Register');
    }

    #[TestDox('User can register successfully')]
    public function test_user_can_register(): void
    {
        $response = $this->post(self::PATH_REGISTER, [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'my_password',
            'password_confirmation' => 'my_password',
        ]);

        $response->assertRedirect(self::PATH_DASHBOARD);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);

        $this->assertAuthenticated();
    }

    #[TestDox('Registration fails with existing email')]
    public function test_registration_fails_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $response = $this->from(self::PATH_REGISTER)->post(self::PATH_REGISTER, [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'my_password',
            'password_confirmation' => 'my_password',
        ]);

        $response->assertRedirect(self::PATH_REGISTER);
        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    #[TestDox('Registration fails with password mismatch')]
    public function test_registration_fails_password_confirmation(): void
    {
        $response = $this->from(self::PATH_REGISTER)->post(self::PATH_REGISTER, [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'my_password',
            'password_confirmation' => 'wrong_password',
        ]);

        $response->assertRedirect(self::PATH_REGISTER);
        $response->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    #[TestDox('Authenticated user redirected away from register page')]
    public function test_logged_in_redirect(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(self::PATH_REGISTER);

        $response->assertRedirect(self::PATH_DASHBOARD);
    }
}
