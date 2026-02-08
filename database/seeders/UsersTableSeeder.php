<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Role;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear previous data
        User::truncate();
        DB::table('role_user')->truncate();

        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();
        $employerRole = Role::where('name', 'employer')->first(); // new role

        // ===== CREATE ADMIN USERS =====
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin')
        ]);

        $admin1 = User::create([
            'name' => 'Administrator(Leo)',
            'email' => 'l30pmsit@gmail.com',
            'password' => Hash::make('1234')
        ]);

        // Attach admin role
        $admin->roles()->attach($adminRole);
        $admin1->roles()->attach($adminRole);

        // ===== CREATE EMPLOYERS =====
        foreach(range(1,5) as $i) {
            $employer = User::create([
                'name' => 'Employer'.$i,
                'email' => 'employer'.$i.'@mail.com',
                'password' => Hash::make('employer')
            ]);
            $employer->roles()->attach($employerRole);
        }

        // ===== CREATE USERS / JOB SEEKERS =====
        // First default user
        $user = User::create([
            'name' => 'User',
            'email' => 'user@mail.com',
            'password' => Hash::make('user')
        ]);
        $user->roles()->attach($userRole);

        // Additional 20 users
        foreach(range(1,20) as $i) {
            $u = User::create([
                'name' => 'User'.$i,
                'email' => 'user'.$i.'@mail.com',
                'password' => Hash::make('user')
            ]);
            $u->roles()->attach($userRole);
        }
    }
}
