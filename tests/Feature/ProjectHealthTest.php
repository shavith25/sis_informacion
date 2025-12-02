<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectHealthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function la_página_de_inicio_se_carga_correctamente()
    {
      
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SISTEMA DE INFORMACIÓN DE ÁREAS PROTEGIDAS'); 
    }

    /** @test */
    public function todas_las_rutas_principales_son_accesibles()
    {
        // Define las rutas principales que deseo probar
        $routes = [
            route('zonas.mapa'),
            //route('zonas.registrada'),
            route('zonas.landing'),
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200); // Verifica que cada ruta devuelva un estado HTTP 200
        }
    }
}
