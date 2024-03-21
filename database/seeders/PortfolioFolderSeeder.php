<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Repository\AwsHandler;

class PortfolioFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aws = new AwsHandler;
        $aws->createFolder("user-porfolio");
    }
}
