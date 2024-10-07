<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{

    public function definition(): array
    {
        return [
            'post_id' => function () {
                return \App\Models\Post::query()->inRandomOrder()->first()->id;
            },
            'user_id' => function () {
                return \App\Models\User::query()->inRandomOrder()->first()->id;
            },
            'body' => $this->faker->text
        ];
    }
}
