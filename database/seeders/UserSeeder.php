<?php

namespace Database\Seeders;

use Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table("users")->insert([
            "name" => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234')
        ]);
    }
}
