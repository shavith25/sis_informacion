<?php 

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role; 
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsuariosTest extends TestCase 
{
    use RefreshDatabase;

    /** @test */
    public function verificar_que_el_administrador_esta_logueado_y_tiene_acceso() 
    {
        // 1. Preparación: Crear el rol y el usuario administrador
        $adminRole = Role::create(['name' => 'Administrador']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole); 

        // 2. Acción: Intentar acceder a la gestión de usuarios actuando COMO el admin
        $response = $this->actingAs($admin)->get('/usuarios');

        // 3. Verificaciones de Calidad
        
        // Verifica que el usuario realmente está autenticado en la sesión
        $this->assertAuthenticatedAs($admin);

        // Verifica que puede ver la página (Status 200 OK) y no es redirigido al login
        $response->assertStatus(200);

        // Opcional: Verificar que en la vista aparece un texto que solo ve el admin
        $response->assertSee('Listado de Usuarios'); 
    }

    /** @test */
    public function un_invitado_no_puede_ver_el_listado_de_usuarios()
    {
        // Acción: Intentar acceder sin loguearse
        $response = $this->get('/usuarios');

        // Verificación: Debe ser redirigido al login (302)
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}