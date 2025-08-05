<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
          // User::factory(10)->create();

          DB::table('users')->insert([
               'name' => 'Admin',
               'email' => 'admin@example.com',
               'email_verified_at' => now(),
               'password' => Hash::make('1234'), // La contraseña se cifra aquí
               'remember_token' => Str::random(10),
               'created_at' => now(),
               'updated_at' => now(),
          ]);
    }
}
