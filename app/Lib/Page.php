<?php

namespace App\Lib;

class Page {

    public static function getMenuParams()
    {
        $params = [];
        if(isset($GLOBALS['parameters']['general']['username'])) {
            $params['username'] = $GLOBALS['parameters']['general']['username'];
        }
        return $params;
    }
}