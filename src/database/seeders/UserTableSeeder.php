<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'bggpassword' => 'j51btoq4m2ohjgd7lwmsoedcgkmyqjlr'
        ));
    }

}