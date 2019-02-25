<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;

//    public static function setUpBeforeClass()/* The :void return type declaration that should be here would cause a BC issue */ {
//        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
//
//        Artisan::call('passport:install');
//    }

    public function setUp()
    {
        parent::setUp();

        Artisan::call('passport:install');

        User::create([
            'email' => 'admin@admin.panel',
            'password' => bcrypt('secret')
        ]);
    }

    public function testWillRegisterAUser()
    {
        $response = $this->post('api/register', [
            'email' => 'test2@email.com',
            'password' => '123456',
            'password_confirm' => '123456'
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'success'
                 ]);
    }

    public function testsRegistrationRequiresPasswordAndEmail()
    {
        $this->post('/api/register')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['error' => [
                        'email' => ['The email field is required.'],
                        'password' => ['The password field is required.'],
                        "password_confirm" => ['The password confirm field is required.']
                    ]
                ]);
    }

    public function testLoginRequiresEmailAndPassword()
    {
        $this->post('api/login')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'error' => 'Unauthorized'
            ]);
    }

    public function testWillLogAUserIn()
    {
        $response = $this->post('api/login', [
            'email' => 'admin@admin.panel',
            'password' => 'secret'
        ]);

//        print_r($response);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                    'token'
                 ]);
    }

    public function testWillNotLogAnInvalidUserIn()
    {
        $response = $this->post('api/login', [
            'email'    => 'test@email.com',
            'password' => 'notlegitpassword'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
                 ->assertJson([
                     "error" => "Email authorization error"
                 ]);
    }

    public function testsRequirePasswordConfirmation()
    {
        $payload = [
            'email' => 'admin@admin.panel',
            'password' => 'secret',
            'password_confirm' => '123'
        ];

        $this->post('/api/register', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "error" => [
                   "password_confirm" => ["The password confirm and password must match."]
                ]
            ]);
    }

    public function testUserIsLoggedOutProperly()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = $user->createToken('MyApp')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $this->get('/api/tasks', $headers)->assertStatus(Response::HTTP_OK);
        $this->post('/api/logout', [], $headers)->assertStatus(Response::HTTP_OK);

        $user = User::find($user->id);

        $this->assertEquals(null, $user->api_token);
    }

    public function testUserWithNullToken()
    {
        // Simulating login
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = $user->createToken('MyApp')->accessToken;
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        // Simulating logout
        $user->AauthAcessToken()->delete();

        $this->get('/api/tasks', $headers)->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}