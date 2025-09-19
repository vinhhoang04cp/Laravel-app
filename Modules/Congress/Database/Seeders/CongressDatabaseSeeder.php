<?php

namespace Modules\Congress\Database\Seeders;

use Illuminate\Database\Seeder;

class CongressDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call([
             CongressModuleSeeder::class,
         ]);
    }
}
