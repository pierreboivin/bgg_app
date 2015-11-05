<?php


namespace App\Lib;
use Illuminate\Support\Facades\Auth;


class SessionManager {

    public static function guestConnexion($username) {
        if (Auth::check() && $username == Auth::user()->bggusername) {
            $GLOBALS['parameters']['general']['username'] = Auth::user()->bggusername;
            $GLOBALS['parameters']['general']['password'] = Auth::user()->bggpassword;
            $GLOBALS['parameters']['typeLogin'] = 'login';
        } elseif($username) {
            $GLOBALS['parameters']['general']['username'] = $username;
            $GLOBALS['parameters']['general']['password'] = '';
            $GLOBALS['parameters']['typeLogin'] = 'guest';
        }
    }
    public static function ifLogin() {
        return $GLOBALS['parameters']['typeLogin'] == 'login';
    }

}