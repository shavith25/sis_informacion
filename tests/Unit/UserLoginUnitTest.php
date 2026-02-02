<?php

namespace Tests\Unit;

use Tests\TestCase; // Importante: usar el TestCase de Laravel

class UserLoginUnitTest extends TestCase
{
    /** @test */
    public function un_usuario_con_estado_uno_esta_activo()
    {
        $user = new \App\Models\User();
        $user->estado = 1;

        $this->assertTrue($user->estaActivo(), 'El método debería retornar true para estado 1');
    }

    /** @test */
    public function un_usuario_con_estado_cero_no_esta_activo()
    {
        $user = new \App\Models\User();
        $user->estado = 0;

        $this->assertFalse($user->estaActivo(), 'El método debería retornar false para estado 0');
    }
}