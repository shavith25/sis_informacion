<?php

namespace Tests\Feature;

use App\Models\Zonas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MapaRegistroTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
  public function se_pueden_guardar_datos_en_una_zona()
{
    $zona = Zonas::factory()->create();

    $datosParaGuardar = [
        'flora_fauna' => 'Bosque seco tropical',
        'extension' => '150 km²',
        'poblacion' => '1200 habitantes',
        'provincia' => 'Cochabamba',
        'especies_peligro' => 'Jaguar, Oso andino',
        'otros_datos' => 'Zona con alto valor ecológico',
    ];

    $response = $this->postJson(route('zonas.guardar-datos', ['zona' => $zona->id]), $datosParaGuardar);

    $response->assertStatus(200)
             ->assertJson([
                 'success' => true,
                 'zona_id' => $zona->id,
             ]);

  
    $this->assertDatabaseHas('datos', array_merge($datosParaGuardar, [
        'zona_id' => $zona->id,
    ]));

    
    $this->assertNotNull($zona->fresh()->datos);
    $this->assertEquals('Bosque seco tropical', $zona->fresh()->datos->flora_fauna);
    $this->assertEquals('150 km²', $zona->fresh()->datos->extension);
    $this->assertEquals('1200 habitantes', $zona->fresh()->datos->poblacion);
    $this->assertEquals('Cochabamba', $zona->fresh()->datos->provincia);
}


    /** @test */
   public function no_se_puede_registrar_un_mapa_con_datos_invalidos()
{
    // Crear una zona para usar como parámetro
    $zona = Zonas::factory()->create();

    // Datos inválidos (provincia demasiado largo)
    $data = [
        'flora_fauna' => 'Bosque húmedo',
        'extension' => '200 km²',
        'poblacion' => '500 habitantes',
        'provincia' => str_repeat('A', 200), // inválido: > 100 caracteres
        'especies_peligro' => 'Tigre',
        'otros_datos' => 'Datos adicionales',
    ];

    // Simular el envío de datos inválidos
    $response = $this->postJson(route('zonas.guardar-datos', ['zona' => $zona->id]), $data);

    // Verificar que la respuesta tenga código 422 (errores de validación)
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['provincia']);

    // Verificar que los datos no se hayan guardado en la tabla 'datos'
    $this->assertDatabaseMissing('datos', [
        'zona_id' => $zona->id,
        'provincia' => str_repeat('A', 200),
    ]);
}

}
