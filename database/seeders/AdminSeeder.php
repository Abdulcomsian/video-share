<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "type" => 3,
            "full_name" => "admin",
            "email" => "admin@gmail.com",
            "phone_number" => "12312312",
            "email_verified_at" => Carbon::now(),
            "password" => Hash::make("admin123")
        ]);
    }
}
