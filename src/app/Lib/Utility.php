<?php

namespace App\Lib;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Utility
{
    public static function compareOrderNbPlayed($a, $b)
    {
        return $b['nbPlayed'] - $a['nbPlayed'];
    }

    public static function compareOrderRating($a, $b)
    {
        return floatval($b['rating']) > floatval($a['rating']);
    }

    public static function compareOrderWeight($a, $b)
    {
        return floatval($b['weight']) > floatval($a['weight']);
    }

    public static function compareNumPlays($a, $b)
    {
        return $a['numplays'] - $b['numplays'];
    }

    public static function compareDate($a, $b)
    {
        if (isset($a['date']) && is_int($a['date']) && isset($b['date']) && is_int($b['date'])) {
            return $a['date'] - $b['date'];
        }
        return 0;
    }

    public static function compareRentabilite($a, $b)
    {
        return $b['rentabilite'] < $a['rentabilite'];
    }

    public static function compareOwned($a, $b)
    {
        return $b['nbOwned'] - $a['nbOwned'];
    }

    public static function compareRatingBgg($a, $b)
    {
        return floatval($b['rating_bgg']) > floatval($a['rating_bgg']);
    }

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
     * @param $value
     * @return string
     */
    public static function displayPercent($value)
    {
        return $value . ' %';
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
        return self::replaceAccent(Str::slug($string));
    }

    /**
     * Convert any float collection from 0 to 1 range
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

    public static function bggGetSingleOrMultiple($array) {
        if(isset($array['@attributes'])) {
            return $array['@attributes'];
        } else {
            return $array;
        }
    }
    public static function bggGetMultiple($array) {
        if(isset($array['item'])) {
            if(isset($array['item']['@attributes'])) {
                unset($array['@attributes']);
                return ['item' => $array];
            } else {
                return $array;
            }
        } else {
            return [];
        }
    }

    public static function printMemoryUsage($title = 'Memory') {
        echo $title . ' : ' . memory_get_usage(true) / 1024 / 1024 . '<br>';
    }

}