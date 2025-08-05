<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
     /**
      * Run the database seeds.
      */
     public function run(): void
     {
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
