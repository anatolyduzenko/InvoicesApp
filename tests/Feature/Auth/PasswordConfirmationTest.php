<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        Session::start();

        $response = $this->actingAs($user)->get('/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $user = User::factory()->create();
        Session::start();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $user = User::factory()->create();
        Session::start();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors();
    }
}
