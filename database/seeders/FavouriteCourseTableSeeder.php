<?php

namespace Database\Seeders;

use App\Models\FavouriteCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavouriteCourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FavouriteCourse::factory()->count(10)->create();
    }
}
