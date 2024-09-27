<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'name' => 'Tung Trinh',
            'email' => 'tungtrinh@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'address' => fake()->address,
            'phone_number' => fake()->phoneNumber,
            'birthday' => fake()->date,
        ]);
    }
}
