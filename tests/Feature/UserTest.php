<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function la_ruta_de_registro_no_esta_disponible() 
    {
        // 1. Intentar cargar el formulario de registro por URL manual
        $response = $this->get('/register');

        // 2. Verificar que devuelve 404 porque la desactivaste en web.php
        $response->assertStatus(404);
    }

    /** @test */
    public function los_intentos_de_post_al_registro_deben_fallar()
    {
        // Intentar enviar datos a una ruta que no existe
        $response = $this->post('/register', [
            'email' => "test@testing.com",
            'password' => "Password123",
            'name' => "Testing"
        ]);

        $response->assertStatus(404);
    }
}