<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    protected $model = Course::class;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::factory()->count(100)->create();
    }
}
