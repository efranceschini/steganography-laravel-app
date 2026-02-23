<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    const string PATH_HOME = '/';

    const string PATH_LOGIN = '/login';

    const string PATH_DASHBOARD = '/dashboard';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('my_password'),
        ]);
    }

    #[TestDox('Login page loads')]
    public function test_login_page_loads(): void
    {
        $paths = [self::PATH_HOME, self::PATH_LOGIN];
        foreach ($paths as $path) {
            $response = $this->get($path);

            $response->assertStatus(200);
            $response->assertSee('Login');
        }
    }

    #[TestDox('Successful login')]
    public function test_user_can_login(): void
    {
        $response = $this->post(self::PATH_LOGIN, [
            'email' => $this->user->email,
            'password' => 'my_password',
        ]);

        $response->assertRedirect(self::PATH_DASHBOARD);
        $this->assertAuthenticatedAs($this->user);
    }

    #[TestDox('Login failure with password mismatch')]
    public function test_login_fails_invalid_password(): void
    {
        $response = $this->from('/')
            ->post(self::PATH_LOGIN, [
                'email' => $this->user->email,
                'password' => 'wrong_password',
            ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    #[TestDox('Login failure with email mismatch')]
    public function test_login_fails_invalid_email(): void
    {
        $response = $this->from('/')
            ->post(self::PATH_LOGIN, [
                'email' => 'not@my.email',
                'password' => 'my_password',
            ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    #[TestDox('Successful login redirects to dashboard')]
    public function test_logged_in_redirect(): void
    {
        $paths = [self::PATH_HOME, self::PATH_LOGIN];
        foreach ($paths as $path) {
            $response = $this->actingAs($this->user)->get($path);

            $response->assertRedirect(self::PATH_DASHBOARD);
        }
    }
}
