<?php

namespace Modules\Vote\Database\Seeders;

use Illuminate\Database\Seeder;

class VoteDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call([
             VotePermissionSeeder::class,
         ]);
    }
}
