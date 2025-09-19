<?php

namespace Modules\Shareholder\Database\Seeders;

use Illuminate\Database\Seeder;

class ShareholderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call([
             MenuAndPermissionSeeder::class,
         ]);
    }
}
