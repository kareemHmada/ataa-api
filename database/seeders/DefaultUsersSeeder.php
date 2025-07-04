<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Donor user
        User::updateOrCreate(
            ['email' => 'donor@ataa.com'],
            [
                'name'     => 'Default Donor kareem',
                'email'    => 'donor@ataa.com',
                'password' => Hash::make('12345678'),
                'role'     => 'donor',
            ]
        );

        // Org user
        User::updateOrCreate(
            ['email' => 'org@ataa.com'],
            [
                'name'              => ' kmal Ataa Organization',
                'email'             => 'org@ataa.com',
                'password'          => Hash::make('12345678'),
                'role'              => 'org',
                'institution_name'  => 'Ataa Foundation',
                'license_number'    => 'ATAA-12345',
            ]
        );
        
    }
}
