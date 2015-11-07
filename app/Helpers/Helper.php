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
    public static function ifLogin() {
        return SessionManager::ifLogin();
    }


}