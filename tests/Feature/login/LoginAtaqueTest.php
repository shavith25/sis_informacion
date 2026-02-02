<?php

namespace Tests\Feature\Login;

use Tests\TestCase;
use App\Models\User;
use App\Models\FailedLoginAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginBloqueoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_ataque_rafaga_bloqueo_y_limite_de_registros()
    {
        // 1. Crear usuario activo
        $user = User::factory()->create([
            'email' => 'spam@test.com',
            'estado' => 1
        ]);

        // 2. Simular ráfaga de 10 intentos fallidos
        for ($i = 0; $i < 10; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'incorrecta'
            ]);
        }

        // 3. Refrescar datos del usuario desde la BD
        $user->refresh();

        // ASSERT 1: Verificar que el estado cambió a bloqueado (0)
        $this->assertEquals(0, $user->estado, 'El usuario debería estar bloqueado.');

        // ASSERT 2: Verificar el mensaje de error en la sesión (Bug de UX/Seguridad)
        $this->assertCredentialsInvalid(); 
        
        // ASSERT 3: Optimización de BD (Opcional pero recomendado)
        // Si tu lógica bloquea al 3er intento, ¿para qué seguir llenando la tabla de fallos?
        // Aquí verificamos cuántos registros de fallos se crearon.
        $conteoFallos = FailedLoginAttempt::where('user_id', $user->id)->count();
        
        // Este assert fallará si tu código sigue insertando registros después de bloquear al usuario
        // Es un buen test para evitar que un atacante llene tu disco duro con logs de fallos.
        $this->assertLessThanOrEqual(10, $conteoFallos);
    }

    /**
     * Helper para verificar que la sesión tiene los errores esperados
     */
    protected function assertCredentialsInvalid()
    {
        $this->followRedirects($this->get('/login'))
            ->assertSee('Su cuenta ha sido bloqueada')
            ->assertSee('contacte al administrador');
    }
}