<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'name' => 'Administrateur',
            'slug' => 'ROLE_ADMIN',
            'level' => 900,
        ]);
        DB::table('roles')->insert([
            'name' => 'Utilisateur Premium',
            'slug' => 'PREMIUM_USER',
            'level' => 100,
        ]);
        DB::table('roles')->insert([
            'name' => 'Utilisateur',
            'slug' => 'ROLE_USER',
            'level' => 1,
        ]);
    }
}
