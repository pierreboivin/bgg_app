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
            'bggpassword' => 'ayw2n5ks1ucjd6dtul5vooqkkanj5js6'
        ));
    }

}