<?php

namespace App\Lib;
use Illuminate\Support\Facades\Auth;

class SessionManager {

    public static function guestConnexion($username) {
        if (Auth::check()) {
            $GLOBALS['parameters']['login']['username'] = $GLOBALS['parameters']['general']['username'] = Auth::user()->bggusername;
            $GLOBALS['parameters']['login']['password'] = Auth::user()->bggpassword;
            $GLOBALS['parameters']['typeLogin'] = 'login';
        } else {
            $GLOBALS['parameters']['typeLogin'] = 'guest';
            $GLOBALS['parameters']['login']['password'] = '';
        }
        if($username) {
            $GLOBALS['parameters']['general']['username'] = $username;
        }
    }
    public static function ifLogin() {
        return $GLOBALS['parameters']['typeLogin'] == 'login' && $GLOBALS['parameters']['general']['username'] == $GLOBALS['parameters']['login']['username'];
    }

}