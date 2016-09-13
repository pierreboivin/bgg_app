<?php


namespace App\Lib;

use App\Cache;

class PersistentCache
{
    public static function put($keyCache, $content)
    {
        Cache::createOrUpdate(array('identifier' => $keyCache, 'data' => $content, 'username' => $GLOBALS['parameters']['general']['username']), array('identifier' => $keyCache));
    }
    public static function has($keyCache)
    {
        return Cache::where(array('identifier' => $keyCache))->first() != null;
    }
    public static function get($keyCache)
    {
        return Cache::where(array('identifier' => $keyCache))->pluck('data');
    }
    public static function flush()
    {
        return Cache::truncate();
    }
}