<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\JobSeeker;
use Illuminate\Support\Str;

class JobSeekerSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users with role 'employee' or 'user'
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'employee'); // change 'employee' if your role name is 'user'
        })->get();

        foreach ($users as $user) {
            JobSeeker::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => '0917'.rand(1000000,9999999),
                    'address' => 'Sample Address '.rand(1,100),
                    'birthdate' => now()->subYears(rand(20,35))->format('Y-m-d'),
                    'gender' => ['male','female','other'][rand(0,2)],
                    'resume' => null,
                    'profile_summary' => 'This is a sample profile summary for '.$user->name,
                ]
            );
        }
    }
}
