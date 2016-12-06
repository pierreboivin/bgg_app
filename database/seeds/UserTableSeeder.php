<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();
        User::create(array(
            'name'     => 'Pierre Boivin',
            'email'    => 'pierreboivin85@gmail.com',
            'password' => Hash::make('test'),
            'type' => 'admin',
            'bggusername' => 'pboivin',
            'bggpassword' => 'tzxbtt66kp1jb4sf5fnappf18x6tw6st'
        ));
    }

}