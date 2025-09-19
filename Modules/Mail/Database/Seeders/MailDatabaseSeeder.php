<?php

namespace Modules\Mail\Database\Seeders;

use Illuminate\Database\Seeder;

class MailDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call([
             SeedMenuMailSeeder::class,
             SeedPermissionMailSeeder::class,
             SeedTemplateMailSeeder::class,
         ]);
    }
}
