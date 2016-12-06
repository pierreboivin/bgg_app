<?php

namespace App\Lib;
use Illuminate\Support\Facades\Auth;

class SessionManager {

    public static function manageConnexionInfo($usernameParameter) {
        /*
         * $GLOBALS['parameters']['login']['type'] sera admin, normal, nobgg ou guest
         */
        if (Auth::check()) {
            $GLOBALS['parameters']['login']['username'] = $GLOBALS['parameters']['general']['username'] = Auth::user()->bggusername;
            $GLOBALS['parameters']['login']['password'] = Auth::user()->bggpassword;
            $GLOBALS['parameters']['login']['authenticated'] = true;
            $GLOBALS['parameters']['login']['type'] = Auth::user()->type;
            $GLOBALS['parameters']['typeLogin'] = 'login';
            // Utilisateur connecté, mais sans avoir d'utilisateur BGG
            if(!Auth::user()->bggusername) {
                $GLOBALS['parameters']['login']['type'] = 'nobgg';
                $GLOBALS['parameters']['login']['username'] = Auth::user()->name;
            }
        } else {
            $GLOBALS['parameters']['login']['password'] = '';
            $GLOBALS['parameters']['login']['type'] = 'guest';
            $GLOBALS['parameters']['login']['authenticated'] = false;
            $GLOBALS['parameters']['typeLogin'] = 'guest';
        }
        if($usernameParameter) {
            $GLOBALS['parameters']['general']['username'] = $usernameParameter;
        }
    }
    public static function ifLoginAsSelf() {
        return $GLOBALS['parameters']['login']['authenticated'] && $GLOBALS['parameters']['general']['username'] == $GLOBALS['parameters']['login']['username'];
    }
    public static function ifBggInfo() {
        return (isset($GLOBALS['parameters']['login']['type']) && $GLOBALS['parameters']['login']['type'] !== 'nobgg') || (isset($GLOBALS['parameters']['general']['username']) && $GLOBALS['parameters']['general']['username']);
    }

}