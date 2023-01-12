<?php

namespace App\Lib;

use Illuminate\Support\Facades\Auth;

class Page {

    public static function getMenuParams()
    {
        $params = [];
        if(isset($GLOBALS['parameters']['general']['username'])) {
            $params['username'] = $GLOBALS['parameters']['general']['username'];
        }
        if(!SessionManager::ifBggInfo() && Auth::check()) {
            $params['name'] = Auth::user()->name;
        }
        return $params;
    }
}