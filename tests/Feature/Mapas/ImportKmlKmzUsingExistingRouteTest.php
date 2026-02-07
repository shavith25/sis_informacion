<?php

namespace Tests\Feature\Mapas;

use App\Models\Area;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImportKmlKmzUsingExistingRouteTest extends TestCase
{
    use RefreshDatabase;

    private function login(): void
    {
        $this->actingAs(User::factory()->create());
    }

    public function test_importa_kml_usando_store_zona(): void
    {
        $this->login();

        $area = Area::factory()->create();

        $kml = UploadedFile::fake()->create('zona.kml', 5, 'text/plain');

        $payload = [
            'zona_nombre' => 'Zona Importada KML',
            'descripcion_zona' => 'Importación de prueba',
            'area_id' => $area->id,
            'tipo_coordenada' => 'Poligono',
            'coordenadas' => json_encode([
                ['lat' => -21.5, 'lng' => -64.7],
            ]),
            'kml_kmz' => $kml, 
        ];

        $response = $this->post(route('mapa-areas.storeZonaFromMapa'), $payload);

        $response->assertOk()
                ->assertJson([
                    'message' => 'Zona registrada correctamente.',
                ]);
    }

    public function test_importa_kmz_usando_store_zona(): void
    {
        $this->login();

        $area = Area::factory()->create();

        $kmz = UploadedFile::fake()->create('zona.kmz', 5, 'application/zip');

        $payload = [
            'zona_nombre' => 'Zona Importada KMZ',
            'descripcion_zona' => 'Importación de prueba',
            'area_id' => $area->id,
            'tipo_coordenada' => 'Poligono',
            'coordenadas' => json_encode([
                ['lat' => -21.5, 'lng' => -64.7],
            ]),
            'kml_kmz' => $kmz,
        ];

        $response = $this->post(route('mapa-areas.storeZonaFromMapa'), $payload);

        $response->assertOk()
                ->assertJson([
                    'message' => 'Zona registrada correctamente.',
                ]);
    }
}
