<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run()
    {
        // Seed the Users
        \App\Models\User::factory(10)->create();

        // Seed the Posts
        \App\Models\Post::factory(50)->create();

        // Seed the Comments
        \App\Models\Comment::factory(200)->create();
    }
}
