<?php

namespace App\Lib;

class BGGUrls {

    public static function getGamesOwned()
    {
        return 'http://www.boardgamegeek.com/xmlapi2/collection?own=1&excludesubtype=boardgameexpansion&stats=1&showprivate=1&username=' . urlencode($GLOBALS['parameters']['general']['username']);
    }

    public static function getGamesAndExpansionsOwned()
    {
        return 'http://www.boardgamegeek.com/xmlapi2/collection?own=1&stats=1&showprivate=1&username=' . urlencode($GLOBALS['parameters']['general']['username']);
    }

    public static function getGamesRated()
    {
        return 'http://www.boardgamegeek.com/xmlapi2/collection?stats=1&rated=1&username&excludesubtype=boardgameexpansion&username=' . urlencode($GLOBALS['parameters']['general']['username']);
    }

    public static function getUserInfos()
    {
        return 'http://www.boardgamegeek.com/xmlapi2/user?buddies=1&hot=1&top=1&name=' . urlencode($GLOBALS['parameters']['general']['username']);
    }

    public static function getPlays($i)
    {
        return 'http://www.boardgamegeek.com/xmlapi2/plays?username=' . urlencode($GLOBALS['parameters']['general']['username']) . '&page=' . $i;
    }

    public static function getDetail($i)
    {
        return 'http://boardgamegeek.com/xmlapi2/thing?id=' . $i;
    }
}