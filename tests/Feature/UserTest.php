<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register() 
    {
        Artisan::call('migrate');

        //  El formulario  carga
        $carga = $this->get(route('register'));
        $carga->assertStatus(200)->assertSee('Registrarse');

        // Registro incorrecto
        $registroMal = $this->post(route('do-register'),["email"=>"aaa",
        "password"=>"123"]);
        $registroMal->assertStatus(302)->assertRedirect(route('register'))
        ->assertSessionHasErrors(['email' => __('validation.email',['
            attribute' => 'email']),'password' => __('validation.min.string',
            ['attribute' => 'password','min' => 6])
        ]);

        // Registro correcto
        $registroBien = $this->post(route('do-register'),['email' => "tes@testing.com",
        "password" => "Password1","name"=>"Testing"]);
        $registroBien->assertStatus(302)->assertRedirect(route('home'));
        $this->assertDatabaseHas('users',['email'=>"test@testing.com"]);

        // Registro repetido
        $registroMal = $this->post(route('do-register'),['email'=>"test@testing.com",
        'password'=>"Password1","name"=>"Testing"]);
        $registroMal->assertStatus(302)->assertRedirect(route('register'))->
        assertSessionHasErrors(['email' => __('validation.unique',['
            attribute'=>'email']),'name' => __('validation.unique',['attribute'=>'name'])
        ]);
    }
}
