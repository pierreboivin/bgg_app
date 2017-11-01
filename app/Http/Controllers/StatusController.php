<?php

namespace App\Http\Controllers;

use App\Cache;
use App\User;

class StatusController extends Controller
{
    public function dashboard()
    {
        echo '<p>Number of users in database : ' . count(User::all()) . '</p>';

        echo '<p>Number of cache record in database : ' . count(Cache::all()) . '</p>';

        $caches = Cache::distinct()->get(['username']);

        echo '<ul>';
        foreach($caches as $cache) {
            echo '<li>' . $cache->username . '</li>';
        }
        echo '</ul>';
    }
}
