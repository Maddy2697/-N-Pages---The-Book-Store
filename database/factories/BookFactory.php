<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //generating fake data
            "title" => fake()->text(20),
            "author" => fake()->text(),
            "genre" => fake()->text(10),
            "description" => fake()->paragraph(),
            "isbn" => fake()->ean8(),
            "image" => "http://placeimg.com/480/640/any",
            "published" => now(),
            "publisher" => fake()->name(),
        ];
    }
}