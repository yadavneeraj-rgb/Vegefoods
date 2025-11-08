<?php

namespace Database\Seeders;

use App\Models\ShopingModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['name' => 'Grocery'],
            ['name' => 'Shop'],
            ['name' => 'Medical']
        ];

        foreach ($modules as $module) {
            DB::table('shoping_modules')->updateOrInsert(
                ['name' => $module['name']],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
