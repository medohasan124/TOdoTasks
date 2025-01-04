<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;


 /*

        login testing
    */


public function test_user_can_login(){

    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('123123'),
    ]);

    $loginData = [
        'email' => 'test@example.com',
        'password' => '123123',
    ];

    $response = $this->postJson('/api/login' , $loginData);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'token'
    ]);
}

    public function test_user_can_not_login_with_wrong_email(){
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('123123'),
        ]);

        $response = $this->postJson('api/login' , [
            'email' => 'wrong@example',
            'password' => '123123',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials'
        ]);
    }
    public function test_user_can_not_login_with_wrong_password(){
        $user = User::factory()->create([
            'email' => "test@example.com",
            'password' => bcrypt('123123'),
        ]);

        $response = $this->postJson('api/login',[
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials'
        ]);
    }

    public function test_user_can_not_login_without_credintial(){

        $user  = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('123123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'rong-Password',
        ];

        $response = $this->postJson('api/login' , $loginData);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials'
        ]);
    }


    /*

        register testing
    */


    public function test_user_can_register(){

        $registerData = [
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => '123123',
        ];

        $response = $this->postJson('/api/register' , $registerData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'token'
        ]);
    }


    public function test_user_can_not_register_without_name(){
        $loginData = [
            // 'name' => '',
            'email' => 'test1@example.com',
            'password' => '123123',
        ];

        $response = $this->postJson('api/register',$loginData);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The name field is required.'
        ]);
    }

    public function test_user_can_not_register_without_email(){
        $loginData = [
             'name' => 'testing',
            // 'email' => 'test1@example.com',
            'password' => '123123',
        ];

        $response = $this->postJson('api/register',$loginData);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The email field is required.'
        ]);
    }

    public function test_user_can_not_register_without_password(){
        $loginData = [
            'name' => 'testing',
            'email' => 'test1@example.com',
        //    'password' => '123123',
       ];

       $response = $this->postJson('api/register',$loginData);

       $response->assertStatus(422);
       $response->assertJson([
           'message' => 'The password field is required.'
       ]);
    }

    public function test_user_can_not_register_with_weak_password(){

        $loginData = [
            'name' => 'testing',
            'email' => 'test1@example.com',
           'password' => 'week',
       ];

       $response = $this->postJson('api/register',$loginData);

       $response->assertStatus(422);
       $response->assertJson([
           'message' => 'The password field must be at least 6 characters.',
       ]);
    }


    public function test_user_can_not_register_with_same_email(){


        $user  = User::factory()->create([
            'email' => 'test1@example.com',
        ]);

        $registerData = [
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => '123123',
        ];

        $response = $this->postJson('/api/register' , $registerData);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The email has already been taken.'
        ]);
    }


}

