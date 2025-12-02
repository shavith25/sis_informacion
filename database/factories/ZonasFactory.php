<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Zonas;
use App\Models\Area;
class ZonasFactory extends Factory
{
    protected $model = Zonas::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->words(3, true), // Genera un string de 3 palabras
            'descripcion' => $this->faker->sentence(), // Una oración
            'coordenadas' => [
                [
                    'tipo' => 'marcador',
                    'coordenadas' => [
                        'lat' => $this->faker->latitude(),
                        'lng' => $this->faker->longitude(),
                    ],
                ],
                [
                    'tipo' => 'poligono',
                    'coordenadas' => [
                        ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
                        ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
                        ['lat' => $this->faker->latitude(), 'lng' => $this->faker->longitude()],
                    ],
                ],
            ],
            'estado' => $this->faker->boolean(),
            'area_id' => Area::factory(),
            'tipo_coordenada' => 'geojson'
        ];
    }
}
