<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\{ UserSeeder , FolderSeeder , JobSeeder} ;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(FolderSeeder::class);
        $this->call(JobSeeder::class);

    }
}
