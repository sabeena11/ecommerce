<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \DB::table('users')->delete();
        \DB::table('users')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'Saugat',
                    'email' => 'saugat@shopyee.com',
                    'role' => 1,
                    'password' => bcrypt('s3cret'),
                    'remember_token' => 'd9KR06Il9Nd8uru5Uxv2MYeUdAoPR7VwBgL979gfkOD43fOlnmb8VItw0kg4',
                    'created_at' => '2024-01-02 23:58:28',
                    'updated_at' => '2024-01-05 15:39:06',
                ),

                1 =>
                array (
                    'id' => 2,
                    'name' => 'Sushant',
                    'email' => 'sushant@shopyee.com',
                    'role' => 1,
                    'password' => bcrypt('s3cret'),
                    'remember_token' => 'd9KR06Il9Nd8uru5Uxv2MYeUdAoPR7VwBgL979gfkOD43fOlnmb8VItw0kg5',
                    'created_at' => '2024-02-02 23:58:28',
                    'updated_at' => '2024-02-05 16:39:06',
                ),

                1 =>
                array (
                    'id' => 2,
                    'name' => 'Sabina',
                    'email' => 'sabina@shopyee.com',
                    'role' => 1,
                    'password' => bcrypt('s3cret'),
                    'remember_token' => 'd9KR06Il9Nd8uru5Uxv2MYeUdAoPR7VwBgL979gfkOD43fOlnmb8VItw0kg5',
                    'created_at' => '2024-02-02 23:58:28',
                    'updated_at' => '2024-02-05 16:39:06',
                ),
        ));
    }
}
