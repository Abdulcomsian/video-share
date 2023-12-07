<?php

namespace Database\Seeders;

use App\Models\SuggestedSkills;
use Illuminate\Database\Seeder;
use Database\Seeders\{ UserSeeder , FolderSeeder , JobSeeder , CountrySeeder} ;

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
        $this->call(CountrySeeder::class);
        $this->call(SuggestedSkillsSeeder::class);
    }
}
