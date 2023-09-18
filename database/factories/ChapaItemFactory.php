<?php

namespace Database\Factories;

use App\Models\ChapaItem;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapaItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChapaItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'largura' => $this->faker->text(255),
            'comprimento' => $this->faker->text(255),
            'chapa_id' => \App\Models\Chapa::factory(),
        ];
    }
}
