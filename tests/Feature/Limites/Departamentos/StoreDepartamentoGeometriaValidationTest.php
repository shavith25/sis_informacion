<?php

namespace Tests\Feature\Limites\Departamentos;

use App\Models\Departamento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StoreDepartamentoGeometriaValidationTest extends TestCase
{
    use RefreshDatabase;

    private function loginAdmin(): void
    {
        $role = Role::firstOrCreate(['name' => 'Administrador']);

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user);
    }

    private function validPayload(array $overrides = []): array
    {
        $base = [
            'nombre' => 'Cochabamba',
            // GeoJSON válido como string JSON
            'geometria' => json_encode([
                'type' => 'Polygon',
                'coordinates' => [
                    [
                        [-66.0, -17.0],
                        [-66.1, -17.1],
                        [-66.2, -17.2],
                        [-66.0, -17.0],
                    ]
                ],
            ]),
        ];

        return array_merge($base, $overrides);
    }

    public function test_no_crea_departamento_si_geometria_no_se_envia(): void
    {
        $this->loginAdmin();

        $payload = $this->validPayload();
        unset($payload['geometria']);

        $response = $this->from(route('limites.departamentos.create'))
            ->post(route('limites.departamentos.store'), $payload);

        $response->assertRedirect(route('limites.departamentos.create'));
        $response->assertSessionHasErrors(['geometria']);

        $this->assertDatabaseMissing('departamentos', [
            'nombre' => $payload['nombre'],
        ]);
    }

    public function test_no_crea_departamento_si_geometria_esta_vacia(): void
    {
        $this->loginAdmin();

        $payload = $this->validPayload(['geometria' => '']);

        $response = $this->from(route('limites.departamentos.create'))
            ->post(route('limites.departamentos.store'), $payload);

        $response->assertRedirect(route('limites.departamentos.create'));
        $response->assertSessionHasErrors(['geometria']);

        $this->assertDatabaseMissing('departamentos', [
            'nombre' => $payload['nombre'],
        ]);
    }

    public function test_no_crea_departamento_si_geometria_es_null(): void
    {
        $this->loginAdmin();

        $payload = $this->validPayload(['geometria' => null]);

        $response = $this->from(route('limites.departamentos.create'))
            ->post(route('limites.departamentos.store'), $payload);

        $response->assertRedirect(route('limites.departamentos.create'));
        $response->assertSessionHasErrors(['geometria']);

        $this->assertDatabaseMissing('departamentos', [
            'nombre' => $payload['nombre'],
        ]);
    }

    public function test_no_crea_departamento_si_geometria_no_es_json_valido(): void
    {
        $this->loginAdmin();

        $payload = $this->validPayload([
            'geometria' => '{no-es-json',
        ]);

        $response = $this->from(route('limites.departamentos.create'))
            ->post(route('limites.departamentos.store'), $payload);

        $response->assertRedirect(route('limites.departamentos.create'));
        $response->assertSessionHasErrors(['geometria']);

        $this->assertDatabaseMissing('departamentos', [
            'nombre' => $payload['nombre'],
        ]);
    }

    public function test_si_crea_departamento_si_geometria_es_json_valido(): void
    {
        $this->loginAdmin();

        $payload = $this->validPayload();

        $response = $this->post(route('limites.departamentos.store'), $payload);

        $response->assertStatus(302);

        $this->assertDatabaseHas('departamentos', [
            'nombre' => $payload['nombre'],
        ]);
    }
}
