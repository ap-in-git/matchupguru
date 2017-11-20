<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
          "name"=>"Admin",
          "password"=>Hash::make("password"),
          "email"=>"matthewpincus@gmail.com",
          "role"=>"1",
          "verified"=>"1",
          "active"=>"1"
        ]);
    }
}
