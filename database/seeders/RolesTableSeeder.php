<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear previous roles
        Role::truncate();

        Role::create(['name' => 'admin']);     // keep existing
        Role::create(['name' => 'user']);      // keep existing
        Role::create(['name' => 'employer']);  // new role
    }
}
