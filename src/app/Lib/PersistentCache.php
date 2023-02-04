<?php

namespace App\Lib;

use App\Cache;

class PersistentCache
{
    public static function put($keyCache, $content)
    {
        if(isset($GLOBALS['parameters']['general']['username'])) {
            $username = $GLOBALS['parameters']['general']['username'];
        } else {
            $username = 'none';
        }
        Cache::createOrUpdate(
            array('identifier' => $keyCache, 'data' => $content, 'username' => $username),
            array('identifier' => $keyCache)
        );
    }

    public static function has($keyCache)
    {
        return Cache::where(array('identifier' => $keyCache))->first() != null;
    }

    public static function get($keyCache)
    {
        return Cache::where(array('identifier' => $keyCache))->value('data');
    }

    public static function flush()
    {
        return Cache::truncate();
    }
}