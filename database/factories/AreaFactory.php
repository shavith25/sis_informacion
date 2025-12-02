<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Area;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition()
    {
        return [
            'area' => $this->faker->words(2, true),
            'descripcion' => $this->faker->sentence(),
            'estado' => $this->faker->boolean(),
        ];
    }
}
