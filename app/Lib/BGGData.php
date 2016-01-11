<?php

namespace App\Lib;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BGGData
{
    const CACHE_TIME_IN_MINUTES = 1440;

    public static function getGamesOwned()
    {
        $urlBGG = BGGUrls::getGamesOwned();

        return self::getBGGUrl($urlBGG, 'curl',
            ['cookie' => 'bggusername=' . $GLOBALS['parameters']['general']['username'] . '; bggpassword=' . $GLOBALS['parameters']['login']['password']]);
    }

    public static function getGamesAndExpansionsOwned()
    {
        $urlBGG = BGGUrls::getGamesAndExpansionsOwned();

        return self::getBGGUrl($urlBGG, 'curl',
            ['cookie' => 'bggusername=' . $GLOBALS['parameters']['general']['username'] . '; bggpassword=' . $GLOBALS['parameters']['login']['password']]);
    }

    public static function getUserInfos()
    {
        $urlBGG = BGGUrls::getUserInfos();

        return self::getBGGUrl($urlBGG);
    }

    public static function getPlays()
    {
        $arrayAllPlay = array();
        $i = 1;
        while ($i < 100) {
            $urlBGG = BGGUrls::getPlays($i);

            $arrayPlay = self::getBGGUrl($urlBGG);

            if (isset($arrayPlay['play'])) {
                $arrayAllPlay = array_merge($arrayAllPlay, $arrayPlay['play']);
            } else {
                Cache::put('url_plays_' . $GLOBALS['parameters']['general']['username'] . '_' . $GLOBALS['parameters']['typeLogin'], true, self::CACHE_TIME_IN_MINUTES);
                break;
            }

            $i++;
        }
        return $arrayAllPlay;
    }

    public static function getCurrentDataInCache()
    {
        $progression = 0;
        if(self::dataExistInCache(BGGUrls::getUserInfos())) {
            $progression += 10;
        }
        if(self::dataExistInCache(BGGUrls::getGamesOwned())) {
            $progression += 25;
        }
        if(self::dataExistInCache(BGGUrls::getGamesAndExpansionsOwned())) {
            $progression += 25;
        }
        if(Cache::has('url_plays_' . $GLOBALS['parameters']['general']['username'] . '_' . $GLOBALS['parameters']['typeLogin'])) {
            $progression += 40;
        }
        return $progression;
    }

    private static function dataExistInCache($url) {
        if (Cache::has('url_' . $url . '_' . $GLOBALS['parameters']['typeLogin'])) {
            return true;
        } else {
            return false;
        }
    }

    private static function getBGGUrl($url, $mode = 'url', $parameter = [], $numTry = 0)
    {
        $pathFileDebug = app_path() . '/Debug/' . md5($url) . '.txt';
        $keyCache = 'url_' . $url . '_' . $GLOBALS['parameters']['typeLogin'];

        if ($GLOBALS['debugMode'] == 'getDebug') {
            if (file_exists($pathFileDebug)) {
                $contentUrl = file_get_contents($pathFileDebug);
            } else {
                throw new \Exception('You have to write debug file before obtaining it.');
            }
        } else {
            if (Cache::has($keyCache)) {
                $contentUrl = Cache::get($keyCache);
            } else {
                try {
                    if ($mode == 'curl') {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_COOKIE, $parameter['cookie']);
                        curl_setopt($ch, CURLOPT_URL, $url);
                        $contentUrl = curl_exec($ch);
                    } else {
                        $contentUrl = file_get_contents($url);
                    }
                } catch (\Exception $e) {
                    Session::flash('error', 'Réessayez un peu plus tard.');
                    return redirect('home');
                }
                Cache::put($keyCache, $contentUrl, self::CACHE_TIME_IN_MINUTES);
            }

            if ($GLOBALS['debugMode'] == 'writeDebug') {
                file_put_contents($pathFileDebug, $contentUrl);
            }
        }

        @$simpleXmlObject = simplexml_load_string($contentUrl);
        if (!$simpleXmlObject) {
            Cache::forget($keyCache);
        }
        $arrayData = json_decode(json_encode($simpleXmlObject), true);

        if (self::dataInvalid($arrayData)) {
            if ($numTry < 3) {
                Cache::forget($keyCache);
                sleep($numTry * 10);
                $arrayData = self::getBGGUrl($url, $mode, $parameter, ++$numTry);
            } else {
                throw new \Exception('Can\'t get url ' . $url . ' after ' . $numTry . ' try.');
            }
        }

        return $arrayData;
    }

    private static function dataInvalid($arrayData)
    {
        if (isset($arrayData[0]) && strpos($arrayData[0], 'will be processed') !== false) {
            return true;
        }
        return false;
    }

}