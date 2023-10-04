<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory(20)->create();

        \App\Models\User::factory()->create([
            'username' => 'moazzaq',
            'first_name' => 'moaz',
            'last_name' => 'zaq',
            'email' => 'moazzaq@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        \App\Models\Admin::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
