<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginBlockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_es_bloqueado_tras_tres_intentos_fallidos()
    {
        // 1. Preparación: Creamos un usuario activo
        $password = 'password123';
        $user = User::factory()->create([
            'email' => 'test@ejemplo.com',
            'password' => bcrypt($password),
            'estado' => 1, // Activo
        ]);

        // 2. Acción: Realizamos 3 intentos fallidos
        for ($i = 0; $i < 3; $i++) {
            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'contraseña-incorrecta',
            ]);
            
            // Verificamos que regresa al login con error de validación
            $response->assertSessionHasErrors('email');
        }

        // 3. Verificación del Bloqueo: El 4to intento (incluso con clave correcta) debe fallar
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        // 4. Asserts de Calidad
        $response->assertSessionHasErrors('email');
        
        // Verificamos que el mensaje de error sea el de cuenta bloqueada
        $this->assertTrue(session('errors')->get('email')[0] == 'Su cuenta ha sido bloqueada debido a múltiples intentos fallidos. Por favor contacte al administrador.');

        // Verificamos en la base de datos que el estado cambió a 0
        $user->refresh();
        $this->assertEquals(0, $user->estado, "El usuario debería tener estado 0 tras 3 intentos fallidos.");
    }
}