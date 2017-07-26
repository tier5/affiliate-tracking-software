<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_laravel')->insert(['name' => 'Admin','email' => 'admin@gmail.com','password' => bcrypt('123456'), 'url' => 'none','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
    }
}
