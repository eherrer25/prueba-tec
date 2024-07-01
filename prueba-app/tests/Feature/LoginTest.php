<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;

class LoginTest extends TestCase
{
    use DatabaseMigrations;
    

    protected $name = 'Test';
    protected $email = 'test@gmail.com';
    protected $password = 'password';
    protected $errorEmail = 'test';

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_register(): void
    {
        Session::start();

        $response = $this->post('/api/register', [
            'name' => $this->name,
            'email' => $this->email,
            // 'email' => $this->errorEmail,
            'password' => $this->password
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertJsonFragment([
            'id' => 2,
            'name' => $this->name,
            'email' => $this->email
        ]);

        $response->assertStatus(200);

    }

    public function test_login(): void
    {
        Session::start();

        $response = $this->post('/api/login', [
            'email' => 'admin@mail.com',
            'password' => $this->password
        ], [
            'Accept' => 'application/json'
        ]);

        $data = $response->decodeResponseJson();

        $response->assertStatus(200);

    }

    public function test_show(): void
    {
        Session::start();

        $login = $this->post('/api/login', [
            'email' => 'admin@mail.com',
            'password' => $this->password
        ], [
            'Accept' => 'application/json'
        ]);

        $data = $login->decodeResponseJson();

        $user = User::factory()->create();

        $response = $this->get('/api/users/'.$user->id, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$data['token']
        ]);

        $response->assertStatus(200);

    }

    public function test_edit(): void
    {
        Session::start();

        $login = $this->post('/api/login', [
            'email' => 'admin@mail.com',
            'password' => $this->password
        ], [
            'Accept' => 'application/json'
        ]);

        $data = $login->decodeResponseJson();

        $response = $this->put('/api/users/1', [
            'name' => 'Admin2'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$data['token']
        ]);

        $response->assertStatus(200);

    }

    public function test_delete(): void
    {
        Session::start();

        $login = $this->post('/api/login', [
            'email' => 'admin@mail.com',
            'password' => $this->password
        ], [
            'Accept' => 'application/json'
        ]);

        $data = $login->decodeResponseJson();

        $user = User::factory()->create();

        $response = $this->delete('/api/users/delete/'.$user->id, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$data['token']
        ]);

        $response->assertStatus(200);

    }
}
