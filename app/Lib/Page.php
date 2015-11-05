<?php


namespace App\Lib;

use Illuminate\Support\Facades\Auth;

class Page {

    public static function getMenuParams()
    {
        $params = [];
        if(Auth::check()) {
            $params['username'] = Auth::user()->bggusername;
        }
        return $params;
    }
}