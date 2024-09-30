<?php

namespace Database\Seeders;

use App\Models\Instructor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstructorSeeder extends Seeder
{
    protected $model = Instructor::class;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instructor::factory()->count(10)->create();
    }
}
