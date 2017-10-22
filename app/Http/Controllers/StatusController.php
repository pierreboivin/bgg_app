<?php

namespace App\Http\Controllers;

class StatusController extends Controller
{
    public function dashboard()
    {
        echo '<p>Number of users in database : ' . count(\App\User::all()) . '</p>';

        echo '<p>Number of cache record in database : ' . count(\App\Cache::all()) . '</p>';
    }
}
