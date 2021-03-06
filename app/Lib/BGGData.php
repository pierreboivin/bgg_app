<?php

namespace App\Lib;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BGGData
{
    const CACHE_TIME_IN_MINUTES = 1440;

    const LOAD_NUMBER_TRY = 5;

    const LOAD_WAIT_BETWEEN_TRY = 10;

    public static function getGamesOwned()
    {
        $urlBGG = BGGUrls::getGamesOwned();

        return BGGData::manageSingleMultiple(self::getBGGUrl($urlBGG, 'curl',
            ['cookie' => 'bggusername=' . $GLOBALS['parameters']['general']['username'] . '; bggpassword=' . $GLOBALS['parameters']['login']['password']]));
    }

    public static function getGamesPreviouslyOwned()
    {
        $urlBGG = BGGUrls::getGamesPreviouslyOwned();

        return BGGData::manageSingleMultiple(self::getBGGUrl($urlBGG, 'curl',
            ['cookie' => 'bggusername=' . $GLOBALS['parameters']['general']['username'] . '; bggpassword=' . $GLOBALS['parameters']['login']['password']]));
    }

    public static function getGamesOwnedByUserName($username)
    {
        $urlBGG = BGGUrls::getGamesOwnedByUserName($username);

        return BGGData::manageSingleMultiple(self::getBGGUrl($urlBGG));
    }

    public static function getGamesAndExpansionsOwned()
    {
        $urlBGG = BGGUrls::getGamesAndExpansionsOwned();

        return BGGData::manageSingleMultiple(self::getBGGUrl($urlBGG, 'curl',
            ['cookie' => 'bggusername=' . $GLOBALS['parameters']['general']['username'] . '; bggpassword=' . $GLOBALS['parameters']['login']['password']]));
    }

    public static function getUserInfos()
    {
        $urlBGG = BGGUrls::getUserInfos();

        return self::getBGGUrl($urlBGG);
    }

    public static function getGamesRated()
    {
        $urlBGG = BGGUrls::getGamesRated();

        return self::getBGGUrl($urlBGG);
    }

    public static function getHotWithDetails()
    {
        $urlBGG = BGGUrls::getHot();

        $arrayRawGamesHot = self::getBGGUrl($urlBGG);

        $arrayHotGames = [];
        foreach ($arrayRawGamesHot['item'] as $game) {
            $arrayHotGames[$game['@attributes']['id']] = ['name' => $game['name']['@attributes']['value']];
        }

        $arrayHotGamesDetails = BGGData::getDetailOfGames($arrayHotGames);

        foreach ($arrayHotGamesDetails as $gameId => $gameDetail) {
            $arrayHotGames[$gameId]['id'] = $gameId;
            foreach ($gameDetail['link'] as $link) {
                $attributes = $link['@attributes'];
                $label = $attributes['value'];
                $arrayHotGames[$gameId]['detail'][$attributes['type']][] = ['value' => $label, 'id' => $attributes['id']];
            }
        }
        return $arrayHotGames;
    }

    public static function getListOfGames($arrayIds)
    {
        $urlBGG = BGGUrls::getListOfGames($arrayIds);
        $fromBGG = self::getBGGUrl($urlBGG);
        return $fromBGG;
    }

    public static function getDetailOfGame($idGame)
    {
        $urlBGG = BGGUrls::getDetail($idGame);
        $fromBGG = self::getBGGUrl($urlBGG);
        return $fromBGG['item'];
    }

    public static function getDetailOfGames($games)
    {
        $arrayGamesDetails = [];
        $arrayIds = array_keys($games);
        $strIds = implode(',', $arrayIds);
        $urlBGG = BGGUrls::getDetail($strIds);

        $fromBGG = self::getBGGUrl($urlBGG);

        if (isset($fromBGG['item']['@attributes'])) {
            $arrayGamesDetails[$fromBGG['item']['@attributes']['id']] = $fromBGG['item'];
        } else {
            foreach ($fromBGG['item'] as $gameDetail) {
                $arrayGamesDetails[$gameDetail['@attributes']['id']] = $gameDetail;
            }
        }
        return $arrayGamesDetails;
    }

    public static function getDetailOwned(&$arrayRawGamesOwned)
    {
        $arrayGamesDetails = [];
        if (isset($arrayRawGamesOwned['item'])) {
            foreach ($arrayRawGamesOwned['item'] as $key => $game) {
                $arrayId[] = isset($game['@attributes']['objectid']) ? $game['@attributes']['objectid'] : $game['@attributes']['id'];
                $arrayRawGamesOwned['item'][$key]['detail'] = [];
            }
        }

        if ($arrayId[0] !== null) {
            $arrayChunk = array_chunk($arrayId, 40, true);
            foreach ($arrayChunk as $key => $arrayIds) {
                $strIds = implode(',', $arrayIds);

                $urlBGG = BGGUrls::getDetail($strIds);

                $fromBGG = self::getBGGUrl($urlBGG);

                $fromBGG = Utility::bggGetMultiple($fromBGG);

                foreach ($fromBGG['item'] as $gameDetail) {
                    $arrayGamesDetails[$gameDetail['@attributes']['id']] = $gameDetail;
                }
            }
        }

        return $arrayGamesDetails;
    }

    public static function getPlays()
    {
        $arrayAllPlay = array();
        $i = 1;
        while ($i < 50) {
            $urlBGG = BGGUrls::getPlays($i);

            $arrayPlay = self::getBGGUrl($urlBGG);

            if (isset($arrayPlay['play'])) {
                // Only one item
                if (isset($arrayPlay['play']['@attributes'])) {
                    $arrayAllPlay[] = $arrayPlay['play'];
                } else {
                    $arrayAllPlay = array_merge($arrayAllPlay, $arrayPlay['play']);
                }
            } else {
                $switchPlay = 'url_' . md5('plays_' . $GLOBALS['parameters']['general']['username']) . '_' . $GLOBALS['parameters']['typeLogin'];
                Cache::put($switchPlay, true, self::CACHE_TIME_IN_MINUTES);
                PersistentCache::put($switchPlay, true);
                break;
            }

            $i++;
        }
        return $arrayAllPlay;
    }

    public static function getCurrentDataInCache()
    {
        $progression = 0;
        $toRegenerate = false;
        $message = 'Chargement des informations utilisateurs';
        if (self::dataExistInCache(BGGUrls::getUserInfos(), $toRegenerate)) {
            $message = 'Chargement des jeux de la collection';
            $progression += 10;
        }
        if (self::dataExistInCache(BGGUrls::getGamesOwned(), $toRegenerate)) {
            $message = 'Chargement des extensions de la collection';
            $progression += 30;
        }
        if (self::dataExistInCache(BGGUrls::getGamesAndExpansionsOwned(), $toRegenerate)) {
            $message = 'Chargement des jeux évalués';
            $progression += 20;
        }
        if (self::dataExistInCache(BGGUrls::getGamesRated(), $toRegenerate)) {
            $message = 'Chargement des jeux joués';
            $progression += 10;
        }
        if (self::dataExistInCache('plays_' . $GLOBALS['parameters']['general']['username'], $toRegenerate)) {
            $progression += 30;
        }
        return array('progress' => $progression, 'message' => $message, 'regenerate' => $toRegenerate);
    }

    public static function getCurrentUserNameCollectionDataInCache($compare)
    {
        $toRegenerate = false;
        if (self::dataExistInCache(BGGUrls::getGamesOwnedByUserName($compare), $toRegenerate)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getLevelOfLoading()
    {
        $arrayUrls = [BGGUrls::getUserInfos(), BGGUrls::getGamesOwned(), BGGUrls::getGamesAndExpansionsOwned(), BGGUrls::getGamesRated(), 'plays_' . $GLOBALS['parameters']['general']['username']];
        $inTempCache = true;
        $inPersistentCache = true;
        foreach ($arrayUrls as $url) {
            // Si pas dans la cache du type de login
            if (!Cache::has('url_' . md5($url) . '_' . $GLOBALS['parameters']['typeLogin'])) {
                // Si consulté en tant que guest, vérifié dans la cache "login"
                if ($GLOBALS['parameters']['typeLogin'] == 'guest') {
                    if (!Cache::has('url_' . md5($url) . '_login')) {
                        $inTempCache = false;
                    }
                    // Si connecté, la cache doit être rechargé
                } else {
                    $inTempCache = false;
                }
            }
        }
        foreach ($arrayUrls as $url) {
            if (!PersistentCache::has('url_' . md5($url) . '_' . $GLOBALS['parameters']['typeLogin'])) {
                if ($GLOBALS['parameters']['typeLogin'] == 'guest') {
                    if (!PersistentCache::has('url_' . md5($url) . '_login')) {
                        $inTempCache = false;
                    }
                } else {
                    $inTempCache = false;
                }
            }
        }
        if ($inTempCache) {
            return 'temp';
        } elseif ($inPersistentCache) {
            return 'persistent';
        } else {
            return 'none';
        }
    }

    private static function dataExistInCache($url, &$toRegenerate)
    {
        if ($GLOBALS['parameters']['typeLogin'] == 'guest') {
            $tempKeyCache = 'url_' . md5($url) . '_login';
            if (!Cache::has($tempKeyCache)) {
                $toRegenerate = true;
            }
            if (Cache::has($tempKeyCache) || (PersistentCache::has($tempKeyCache) && !isset($_GET['force']))) {
                return true;
            }
        }
        $tempKeyCache = 'url_' . md5($url) . '_' . $GLOBALS['parameters']['typeLogin'];
        if (!Cache::has($tempKeyCache)) {
            $toRegenerate = true;
        }
        if (Cache::has($tempKeyCache) || (PersistentCache::has($tempKeyCache) && !isset($_GET['force']))) {
            return true;
        } else {
            return false;
        }
    }

    private static function getBGGUrl($url, $mode = 'url', $parameter = [], $numTry = 0)
    {
        $keyCache = 'url_' . md5($url);
        if(isset($GLOBALS['parameters']['typeLogin'])) {
            $keyCache .= '_' . $GLOBALS['parameters']['typeLogin'];

            // Si on est pas connecté, mais qu'il existe une cache pour l'utilisateur connecté, on obtient cette dernière
            if ($GLOBALS['parameters']['typeLogin'] == 'guest') {
                $tempKeyCache = 'url_' . md5($url) . '_login';
                if (Cache::has($tempKeyCache) || PersistentCache::has($tempKeyCache)) {
                    $keyCache = $tempKeyCache;
                }
            }
        }

        if (Cache::has($keyCache)) {
            $contentUrl = Cache::get($keyCache);
        } elseif (PersistentCache::has($keyCache) && !isset($_GET['force'])) {
            $contentUrl = PersistentCache::get($keyCache);
        } else {
            try {
                Log::info('Get info from bgg : ' . $url);
                if ($mode == 'curl') {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_COOKIE, $parameter['cookie']);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    $contentUrl = curl_exec($ch);
                } else {
                    $contentUrl = file_get_contents($url);
                }
            } catch (\Exception $e) {
                Log::error($e);
                Session::flash('error', 'Réessayez un peu plus tard.');
                return redirect('home');
            }
            Cache::put($keyCache, $contentUrl, self::CACHE_TIME_IN_MINUTES);
            PersistentCache::put($keyCache, $contentUrl);
        }

        @$simpleXmlObject = simplexml_load_string($contentUrl);
        if (!$simpleXmlObject) {
            Cache::forget($keyCache);
        }
        $arrayData = json_decode(json_encode($simpleXmlObject), true);

        // Retry if data is not valid
        if (self::dataInvalid($arrayData)) {
            if ($numTry < self::LOAD_NUMBER_TRY) {
                Cache::forget($keyCache);
                sleep($numTry * self::LOAD_WAIT_BETWEEN_TRY);
                $arrayData = self::getBGGUrl($url, $mode, $parameter, ++$numTry);
            } else {
                throw new \Exception('Can\'t get url ' . $url . ' after ' . $numTry . ' try.');
            }
        }

        return $arrayData;
    }

    private static function dataInvalid($arrayData)
    {
        if ($arrayData === false) {
            return true;
        }
        if (isset($arrayData[0]) && strpos($arrayData[0], 'will be processed') !== false) {
            return true;
        }
        return false;
    }

    private static function manageSingleMultiple($getBGGUrl)
    {
        if (isset($getBGGUrl['item'][0])) {
            return $getBGGUrl;
        } else {
            $temp = $getBGGUrl['item'];
            unset($getBGGUrl['item']);
            $getBGGUrl['item'][0] = $temp;
            return $getBGGUrl;
        }
    }

}