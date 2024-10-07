<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imageNames = ['profile1.png', 'profile2.png', 'profile3.jpg', 'profile4.png', 'profile5.png', 'profile6.png'];

        return [
            'user_id' => function () {
                return \App\Models\User::query()->inRandomOrder()->first()->id;
            },
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph(5),
            'image' => $this->faker->randomElement($imageNames),
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'featured' => $this->faker->boolean,
            'education' => $this->faker->word,
            'skills' => $this->faker->words(3, true),
            'resume' => $this->faker->url,
            'additional_links' => $this->faker->url,
            'name' => $this->faker->name
        ];
    }
}
