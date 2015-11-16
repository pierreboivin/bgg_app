<?php

namespace App\Lib;

use Illuminate\Support\Facades\Cache;

class BGGData
{
    public static function getGamesOwned()
    {
        $urlBGG = 'http://www.boardgamegeek.com/xmlapi2/collection?own=1&excludesubtype=boardgameexpansion&stats=1&showprivate=1&username=' . $GLOBALS['parameters']['general']['username'];

        return self::getBGGUrl($urlBGG, 'curl',
            ['cookie' => 'bggusername=' . $GLOBALS['parameters']['general']['username'] . '; bggpassword=' . $GLOBALS['parameters']['login']['password']]);
    }

    public static function getGamesAndExpansionsOwned()
    {
        $urlBGG = 'http://www.boardgamegeek.com/xmlapi2/collection?own=1&stats=1&showprivate=1&username=' . $GLOBALS['parameters']['general']['username'];

        return self::getBGGUrl($urlBGG, 'curl',
            ['cookie' => 'bggusername=' . $GLOBALS['parameters']['general']['username'] . '; bggpassword=' . $GLOBALS['parameters']['login']['password']]);
    }

    public static function getUserInfos()
    {
        $urlBGG = 'http://www.boardgamegeek.com/xmlapi2/user?buddies=1&hot=1&top=1&name=' . $GLOBALS['parameters']['general']['username'];

        return self::getBGGUrl($urlBGG);
    }

    public static function getPlays()
    {
        $arrayAllPlay = array();
        $i = 1;
        while ($i < 100) {
            $urlBGG = 'http://www.boardgamegeek.com/xmlapi2/plays?username=' . $GLOBALS['parameters']['general']['username'] . '&page=' . $i;

            $arrayPlay = self::getBGGUrl($urlBGG);

            if (isset($arrayPlay['play'])) {
                $arrayAllPlay = array_merge($arrayAllPlay, $arrayPlay['play']);
            } else {
                break;
            }

            $i++;
        }
        return $arrayAllPlay;
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
                } catch(\Exception $e) {
                    Log::error('Can\'t get url '. $url . ' : ' . $e->getMessage());
                    Session::flash('error', 'Réessayez un peu plus tard.');
                    return redirect('home');
                }
                Cache::put($keyCache, $contentUrl, 1440);
            }

            if ($GLOBALS['debugMode'] == 'writeDebug') {
                file_put_contents($pathFileDebug, $contentUrl);
            }
        }

        @$simpleXmlObject = simplexml_load_string($contentUrl);
        if(!$simpleXmlObject) {
            Cache::forget($keyCache);
        }
        $arrayData = json_decode(json_encode($simpleXmlObject), true);

        if(isset($arrayData[0]) && strpos($arrayData[0], 'will be processed') !== false) {
            if($numTry < 5) {
                Cache::forget($keyCache);
                sleep($numTry + 2);
                self::getBGGUrl($url, $mode, $parameter, $numTry);
            } else {
                throw new \Exception('Can\'t get url ' . $url . ' after ' . $numTry . ' try.');
            }
        }

        return $arrayData;
    }

}