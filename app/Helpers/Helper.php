<?php

namespace App\Helpers;

use App\Lib\SessionManager;

class Helper{
    public static function set_active($route){
        return (\Request::is($route.'/*') || \Request::is($route)) ? "active" : '';
    }
    public static function ifEmptyToolTip($variable){
        if($variable) {
            return $variable;
        } else {
            return '<a href="#" data-toggle="tooltip" title="Vous devez être connecté pour voir cette statistique"><img src="/assets/img/icon_attention.png" /></a>';
        }
    }
    public static function ifLoginAsSelf() {
        return SessionManager::ifLoginAsSelf();
    }
    public static function ifAuthenticated() {
        return isset($GLOBALS['parameters']['login']['authenticated']) && $GLOBALS['parameters']['login']['authenticated'] === true;
    }
    public static function ifBGGInfo() {
        return SessionManager::ifBggInfo();
    }
    public static function ifAdmin() {
        return isset($GLOBALS['parameters']['login']['type']) && $GLOBALS['parameters']['login']['type'] === 'admin';
    }
    public static function asset_timed($path, $secure=null){
        $file = public_path($path);
        if(file_exists($file)){
            return asset($path, $secure) . '?' . filemtime($file);
        }else{
            throw new \Exception('The file "'.$path.'" cannot be found in the public folder');
        }
    }


}