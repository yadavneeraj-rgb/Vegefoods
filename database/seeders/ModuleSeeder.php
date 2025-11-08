<?php

namespace Database\Seeders;

use App\Models\ShopingModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['name' => 'Grocery'],
            ['name' => 'Shop']
        ];

        foreach ($modules as $module) {
            ShopingModule::create(['name'=> $module['name']]);
        }
    }
}
