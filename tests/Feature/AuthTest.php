<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class AuthTest extends TestCase
{

    use RefreshDatabase;

    public function test_register_user_successfully(): void
    {
        $response = $this->post('/register', [
            'username' => 'User 1',
            'email' => 'user1@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'User 1',
            'email' => 'user1@gmail.com',
        ]);

        $lastUserCreated = User::latest()->first();
        $this->assertEquals('User 1', $lastUserCreated['username']);
        $this->assertEquals('user1@gmail.com', $lastUserCreated['email']);

    }

    public function test_register_redirects_to_blogs_page(): void
    {
       
        $response = $this->post('/register', [
            'username' => 'User 1',
            'email' => 'user1@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('blogs');
    }

    public function test_login_redirects_to_blogs_page(): void
    {
        $user = User::create([
            'username' => 'User 1',
            'email' => 'user1@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $response = $this->post('/login', [
            'email' => 'user1@gmail.com',
            'password' => '123456'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('blogs');
    }

    
    public function test_unauthenticated_user_cannot_access_the_blogs_page(): void
    {
        $response = $this->get('/blogs');

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function test_authenticated_user_can_access_the_blogs_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/blogs');

        $response->assertStatus(200);
        $response->assertViewIs('blogs.index');
    }
}
