<?php

namespace Tests\Feature;

use App\Models\Zonas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ZonasMapaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_mapa_page()
    {
        // Preparamos datos en PostgreSQL
        Zonas::factory()->create([
            'nombre' => 'Zonas Protegidas Test',
            'Geom' => null // Si usa PostGIS puedes eliminar geometria aqui
        ]);

        // Ejecutamos la ruta
        $response = $this->get(route('zonas.mapa'));

        // Validaciones
        $response->assertStatus(200); // La página carga
        $response->assertViewIs('zonas.mapa'); // Retorna la vista correcta
        $response->assertSee('Zonas Protegida Test'); // La data aparece
    }
}
