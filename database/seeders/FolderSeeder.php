<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Folder;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $folder =[
            ["name" => "Birthday" , "client_id" => 1],
            ["name" => "Wedding" , "client_id" => 1]
        ];

        Folder::insert($folder);
    }
}
