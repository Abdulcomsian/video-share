<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User};
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [ 
            ["type" => 1 , "full_name" => "Usman Alam" , "email" => "usman@gmail.com" , "password" => Hash::make("nouman123"), "email_verified_at" => date("Y-m-d H:i:s") ],
            ["type" => 2 , "full_name" => "Shahbaz" , "email" => "shahbaz@gmail.com" , "password" => Hash::make("nouman123"), "email_verified_at" => date("Y-m-d H:i:s") ],
            ["type" => 2 , "full_name" => "Talha" , "email" => "talha@gmail.com" , "password" => Hash::make("nouman123"), "email_verified_at" => date("Y-m-d H:i:s") ],
            ["type" => 2 , "full_name" => "Junaid Mustafa" , "email" => "junaid@gmail.com" , "password" => Hash::make("nouman123"), "email_verified_at" => date("Y-m-d H:i:s") ],
         ];

         User::insert($users);
    }
}
