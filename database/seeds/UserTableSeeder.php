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
            'username' => 'woodvine',
            'email'    => 'pierreboivin85@gmail.com',
            'password' => Hash::make('test'),
            'bggusername' => 'pboivin',
            'bggpassword' => 'jaup103w5s68lzocjr10im5v10r32eq2sx10'
        ));
    }

}