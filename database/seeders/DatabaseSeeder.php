<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([[
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'ssid' => 'Haaruufff',
            'ssid_pass' => 'tunggudulu'
        ]]);

        DB::table('switches')->insert([
            [
                'name' => 'Saklar 1',
                'state_status' => 'HIGH',
                'is_actived' => 0
            ],[
                'name' => 'Saklar 2',
                'state_status' => 'HIGH',
                'is_actived' => 0
            ],[
                'name' => 'Saklar 3',
                'state_status' => 'HIGH',
                'is_actived' => 0
            ],[
                'name' => 'Saklar 4',
                'state_status' => 'HIGH',
                'is_actived' => 0
            ]
        ]);

        DB::table('category_taxes')->insert([
            [
                'name' => '450 VA',
                'tax' => 415
            ],
            [
                'name' => '900 VA',
                'tax' => 1352
            ],
            [
                'name' => '1.300 VA',
                'tax' => 1444
            ],
            [
                'name' => '2.200 VA',
                'tax' => 1444
            ],
            [
                'name' => '3.500 - 5.500 VA',
                'tax' => 1699
            ],
            [
                'name' => '6.600 VA',
                'tax' => 1699
            ]
        ]);

        DB::table('taxes')->insert([[
            'category_tax_id' => 1,
            'tax' => 415
        ]]);
    }
}