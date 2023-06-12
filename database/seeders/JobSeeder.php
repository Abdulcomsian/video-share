<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PersonalJob;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $job = [
            [ "client_id" => 1 , "folder_id" => 1 , "title" => "Birthday Video", "description" => "Make Video Quality updated" , "budget" => 30 , "deadline" => "2023-08-19" , "status" => "unawarded"],
            [ "client_id" => 1 , "folder_id" => 2 , "title" => "Wedding Video", "description" => "Make Video Quality Good And Change Background" , "budget" => 50 , "deadline" => "2023-10-11" , "status" => "unawarded"],
        ];

        PersonalJob::insert($job);
    }
}
