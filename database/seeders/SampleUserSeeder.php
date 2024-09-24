<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'email' => 'mail@mail.com',
                'password' => 'password',
                'employee_no' => 1,
                'joining_date' => '2024-04-01',
                'retirement_date' => null,
                'remember_token' => Str::random(10),
                'created_at' => now()
            ],
            [
                'email' => 'mail@mail.com',
                'password' => 'password',
                'employee_no' => 2,
                'joining_date' => '2024-04-01',
                'retirement_date' => null,
                'remember_token' => Str::random(10),
                'created_at' => now()
            ],

        );
    }
}
