<?php

namespace App\Lib;

class BGGUrls {

    public static function getGamesOwned()
    {
        $showPrivate = '&showprivate=1';
        return 'http://www.boardgamegeek.com/xmlapi2/collection?own=1&excludesubtype=boardgameexpansion&stats=1' . $showPrivate . '&username=' . urlencode($GLOBALS['parameters']['general']['username']);
    }

    public static function getGamesPreviouslyOwned()
    {
        $showPrivate = '&showprivate=1';
        return 'http://www.boardgamegeek.com/xmlapi2/collection?prevowned=1&excludesubtype=boardgameexpansion&stats=1' . $showPrivate . '&username=' . urlencode($GLOBALS['parameters']['general']['username']);
    }

    public static function getGamesOwnedByUserName($username)
    {
        $showPrivate = '&showprivate=1';
        return 'http://www.boardgamegeek.com/xmlapi2/collection?own=1&excludesubtype=boardgameexpansion&stats=1' . $showPrivate . '&username=' . urlencode($username);
    }

    public static function getGamesAndExpansionsOwned()
    {
        $showPrivate = '&showprivate=1';
        return 'http://www.boardgamegeek.com/xmlapi2/collection?own=1&stats=1' . $showPrivate . '&username=' . urlencode($GLOBALS['parameters']['general']['username']);
    }

    public static function getGamesRated()
    {
        return 'http://www.boardgamegeek.com/xmlapi2/collection?stats=1&rated=1&excludesubtype=boardgameexpansion&username=' . urlencode($GLOBALS['parameters']['general']['username']);
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
        return 'http://boardgamegeek.com/xmlapi2/thing?id=' . $i . '&stats=1';
    }

    public static function getHot()
    {
        return 'http://boardgamegeek.com/xmlapi2/hot?type=boardgame';
    }

}