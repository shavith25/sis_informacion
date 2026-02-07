<?php

namespace Tests\Feature\Areas;

use App\Models\Area;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToggleEstadoAreaTest extends TestCase
{
    use RefreshDatabase;

    private function login(): void
    {
        $this->actingAs(User::factory()->create()); // guard web
    }

    public function test_no_autenticado_redirige_a_login(): void
    {
        $area = Area::factory()->create(['estado' => 1]);

        $response = $this->patch(route('areas.toggle', $area));

        $response->assertRedirect(route('login'));
    }

    public function test_desactiva_area_activa(): void
    {
        $this->login();

        $area = Area::factory()->create(['estado' => 1]);

        $response = $this->patch(route('areas.toggle', $area));

        $response->assertStatus(302);

        $area->refresh();
        $this->assertSame(0, (int) $area->estado);
    }

    public function test_activa_area_inactiva(): void
    {
        $this->login();

        $area = Area::factory()->create(['estado' => 0]);

        $response = $this->patch(route('areas.toggle', $area));
        $response->assertStatus(302);

        $area->refresh();
        $this->assertSame(1, (int) $area->estado);
    }
}
