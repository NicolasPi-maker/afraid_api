<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
	public function run(): void
	{
        DB::table('users')->insert([
            'name' => 'Pinier',
            'firstname' => 'Nicolas',
            'email' => 'nicolas_pinier@hotmail.fr',
            'password' => Hash::make('123456'),
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
	}
}
