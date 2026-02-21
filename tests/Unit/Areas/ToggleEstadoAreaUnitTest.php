<?php

namespace Tests\Unit\Areas;

use App\Models\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToggleEstadoAreaUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_desactiva_area_activa(): void
    {
        $area = Area::factory()->create([
            'area' => 'PARQUE NACIONAL CARRASCO',
            'estado' => true,
        ]);

        $area->toggleEstado();

        $area->refresh();
        $this->assertFalse((bool) $area->estado);
    }

    public function test_activa_area_inactiva(): void
    {
        $area = Area::factory()->create([
            'area' => 'PARQUE NACIONAL CARRASCO',
            'estado' => false,
        ]);

        $area->toggleEstado();

        $area->refresh();
        $this->assertTrue((bool) $area->estado);
    }

    public function test_toggle_es_reversible(): void
    {
        $area = Area::factory()->create([
            'area' => 'PARQUE NACIONAL CARRASCO',
            'estado' => true,
        ]);

        $area->toggleEstado(); // true -> false
        $area->toggleEstado(); // false -> true

        $area->refresh();
        $this->assertTrue((bool) $area->estado);
    }
}
