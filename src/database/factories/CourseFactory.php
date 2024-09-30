<?php

namespace Database\Factories;

use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'instructor_id' => Instructor::factory(),
            'description' => fake()->sentence,
            'price' => fake()->randomFloat(2, 10, 200),
            'release_date' => fake()->date()
        ];
    }
}
