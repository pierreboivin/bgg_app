<?php

namespace App\Lib;

use Carbon\Carbon;

class Utility
{
    /**
     * @param $gameId
     * @return string
     */
    public static function urlToGame($gameId) {
        return 'http://boardgamegeek.com/boardgame/' . $gameId;
    }

    /**
     * @param $array
     * @param string $seperator
     * @param string $wrapper
     * @return string
     */
    public static function implodeWrap($array, $seperator = ',', $wrapper = '\'') {
        return $wrapper . implode($wrapper . $seperator . $wrapper, $array) . $wrapper; // Return 'A', 'B', 'C'
    }

    /**
     * Increment value of an array, will create an index if doesn't exist
     * @param $array
     * @param $identifier
     * @param $value
     */
    public static function arrayIncrementValue(&$array, $identifier, $value, $subkey = null)
    {
        if (isset($array[$identifier])) {
            if($subkey) {
                if(isset($array[$identifier][$subkey])) {
                    $array[$identifier][$subkey] += $value;
                } else {
                    $array[$identifier][$subkey] = $value;
                }
            } else {
                $array[$identifier] += $value;
            }
        } else {
            if ($subkey) {
                $array[$identifier][$subkey] = $value;
            } else {
                $array[$identifier] = $value;
            }
        }
    }

    /**
     * @param $dateEnglishFormat Example 2015-05-01
     * @return int
     */
    public static function dateToYearMonthTimestamp($dateEnglishFormat)
    {
        $yearMonth = substr($dateEnglishFormat, 0, 7);
        $dateTStamp = strtotime($yearMonth);
        $dateFromDate = Carbon::create(date("Y", $dateTStamp), date("n", $dateTStamp), 1, 0, 0, 0);
        $timestampDate = $dateFromDate->timestamp;
        return $timestampDate;
    }

    /**
     * @param $dateEnglishFormat Example 2015-05-01
     * @return int
     */
    public static function dateToYear($dateEnglishFormat)
    {
        $yearMonth = substr($dateEnglishFormat, 0, 4);
        return $yearMonth;
    }

    /**
     * @param $dateEnglishFormat Example 2015-05-01
     * @return Carbon Date
     */
    public static function dateStrToCarbon($dateEnglishFormat)
    {
        return Carbon::createFromTimestamp(strtotime($dateEnglishFormat));
    }
    /**
     * @param $dateEnglishFormat Example 2015-05-01
     * @return int
     */
    public static function dateToDayWeek($dateEnglishFormat)
    {
        $dateTStamp = strtotime($dateEnglishFormat);
        return date("N", $dateTStamp);
    }

    /**
     * @param $dateEnglishFormat Example 2015-05-01
     * @return int
     */
    public static function dateToTimestamp($dateEnglishFormat)
    {
        return strtotime($dateEnglishFormat);
    }

    /**
     * @param $totalGamesValue
     * @return string
     */
    public static function displayMoney($totalGamesValue)
    {
        return number_format($totalGamesValue, 2, '.', '') . ' $';
    }

    /**
     * @param $string
     * @return string
     */
    public static function replaceAccent($string)
    {
        return strtr($string,
            'àâäåãáÂÄÀÅÃÁæÆçÇéèêëÉÊËÈïîìíÏÎÌÍñÑöôóòõÓÔÖÒÕùûüúÜÛÙÚÿ',
            'aaaaaaaaaaaaaacceeeeeeeeiiiiiiiinnoooooooooouuuuuuuuy');
    }

    /**
     * @param $string
     * @return string
     */
    public static function getKeyByString($string)
    {
        return self::replaceAccent(str_slug($string));
    }

    /**
     * @param $array
     * @param $key
     * @return mixed
     */
    public static function normalizeArray(&$array, $key)
    {
        $values = [];
        foreach($array as $item) {
            $values[] = $item[$key];
        }
        $minValue = min($values);
        $maxValue = max($values);

        foreach($array as $i => $item) {
            if(($maxValue - $minValue) > 0) {
                $array[$i][$key] = ($item[$key] - $minValue) / ($maxValue - $minValue);
            }
        }

        return $array;
    }

}