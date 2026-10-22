<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
        $response->assertSee('CampusVoice');
    }

    public function test_register_page_renders_successfully(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Create Student Account');
    }

    public function test_student_can_register_with_valid_data(): void
    {
        $response = $this->post('/register', [
            'name' => 'Aditya Sharma',
            'email' => 'aditya@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'aditya@example.com',
            'role' => 'student',
        ]);
    }

    public function test_registration_requires_confirmed_password(): void
    {
        $response = $this->post('/register', [
            'name' => 'Aditya Sharma',
            'email' => 'aditya@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'DifferentPassword',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('Password@123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
