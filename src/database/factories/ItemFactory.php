<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'user_id' => \App\Models\User::factory(),
      'name' => $this->faker->word,
      'brand' => $this->faker->company,
      'description' => $this->faker->sentence,
      'price' => $this->faker->numberBetween(100, 10000),
      'condition' => '良好',
      'img_url' => 'null',
      'is_sold' => false,
    ];
  }
}
