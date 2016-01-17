<?php

namespace App\Lib;

class Stats
{
    /**
     * @param $arrayGamesPlays
     * @return array
     */
    public static function getPlaysRelatedArrays($arrayGamesPlays)
    {
        $arrayTotalPlays = [];
        $arrayPlaysByMonth = [];
        $arrayPlaysByYear = [];
        $arrayPlaysByDayWeek = [];
        $allDatesPlayed = [];
        $countAllPlays = 0;
        foreach ($arrayGamesPlays as $play) {
            $idGame = $play['item']['@attributes']['objectid'];
            $quantityPlay = $play['@attributes']['quantity'];
            $datePlay = $play['@attributes']['date'];

            if ($datePlay != '0000-00-00') {
                $allDatesPlayed[] = $datePlay;
                $timestampDate = Utility::dateToYearMonthTimestamp($datePlay);
                Utility::arrayIncrementValue($arrayPlaysByMonth[$timestampDate], $idGame, $quantityPlay, 'nbPlayed');

                $timestampDate = Utility::dateToDayWeek($datePlay);
                Utility::arrayIncrementValue($arrayPlaysByDayWeek, $timestampDate, $quantityPlay);

                $timestampDate = Utility::dateToYear($datePlay);
                Utility::arrayIncrementValue($arrayPlaysByYear[$timestampDate], $idGame, $quantityPlay, 'nbPlayed');
            }

            $countAllPlays += $quantityPlay;

            Utility::arrayIncrementValue($arrayTotalPlays, $idGame, $quantityPlay, 'nbPlayed');
            $arrayTotalPlays[$idGame]['name'] = $play['item']['@attributes']['name'];
            $arrayTotalPlays[$idGame]['plays'][] = [
                'date' => Utility::dateToTimestamp($datePlay),
                'quantity' => $quantityPlay
            ];
        }

        // Get first play of each game
        foreach($arrayTotalPlays as $idGame => $game) {
            uasort($game['plays'], 'self::compareDate');
            $firstPlay = last($game['plays']);
            $arrayTotalPlays[$idGame]['firstPlay'] = $firstPlay['date'];
        }

        arsort($allDatesPlayed);
        arsort($arrayTotalPlays);
        ksort($arrayPlaysByYear);
        ksort($arrayPlaysByMonth);
        ksort($arrayPlaysByDayWeek);

        $hindex = self::getHIndex($arrayTotalPlays);

        $GLOBALS['data']['arrayTotalPlays'] = $arrayTotalPlays;
        $GLOBALS['data']['arrayPlaysByMonth'] = $arrayPlaysByMonth;
        $GLOBALS['data']['arrayPlaysByYear'] = $arrayPlaysByYear;
        $GLOBALS['data']['arrayPlaysByDayWeek'] = $arrayPlaysByDayWeek;
        $GLOBALS['data']['countAllPlays'] = $countAllPlays;
        $GLOBALS['data']['hindex'] = $hindex;

        if(count($allDatesPlayed) > 0) {
            $GLOBALS['data']['firstDatePlayRecorded'] = Utility::dateStrToCarbon(last($allDatesPlayed));
            $GLOBALS['data']['nbDaysSinceFirstPlay'] = $GLOBALS['data']['firstDatePlayRecorded']->diffInDays();
        }
    }

    private static function compareDate($a, $b)
    {
        return $b['date'] - $a['date'];
    }

    /**
     * @param $arrayGamesAndExpansionsOwned
     * @return array
     */
    public static function getAcquisitionRelatedArrays($arrayGamesAndExpansionsOwned)
    {
        $acquisitionsByMonth = [];
        $totalWithAcquisitionDate = 0;
        $arrayValuesGames = [];
        $totalGamesValue = 0;
        foreach ($arrayGamesAndExpansionsOwned['item'] as $game) {
            if (isset($game['privateinfo'])) {
                $privateProperties = $game['privateinfo']['@attributes'];
                $idGame = $game['@attributes']['objectid'];
                if ($privateProperties['acquisitiondate']) {
                    $timestampDate = Utility::dateToYearMonthTimestamp($privateProperties['acquisitiondate']);
                    Utility::arrayIncrementValue($acquisitionsByMonth[$timestampDate], $idGame, $game['name']);
                    $totalWithAcquisitionDate++;
                }
                if ($privateProperties['currvalue']) {
                    $gameValue = $privateProperties['currvalue'];
                } elseif ($privateProperties['pricepaid']) {
                    $gameValue = $privateProperties['pricepaid'];
                }
                $totalGamesValue += $gameValue;
                Utility::arrayIncrementValue($arrayValuesGames, $idGame, $gameValue);
            }
        }
        ksort($acquisitionsByMonth);
        $GLOBALS['data']['acquisitionsByMonth'] = $acquisitionsByMonth;
        $GLOBALS['data']['totalWithAcquisitionDate'] = $totalWithAcquisitionDate;
        $GLOBALS['data']['arrayValuesGames'] = $arrayValuesGames;
        $GLOBALS['data']['totalGamesValue'] = $totalGamesValue;
    }

    private static function getHIndex($arrayTotalPlays)
    {
        $i = 0;
        foreach ($arrayTotalPlays as $game) {
            if ($game['nbPlayed'] > $i) {
                $i++;
            } else {
                break;
            }
        }
        return $i;
    }

    public static function getCollectionArrays($arrayRawGamesOwned)
    {
        $arrayGameCollection = [];
        foreach ($arrayRawGamesOwned['item'] as $game) {
            $arrayGameCollection[$game['@attributes']['objectid']] = [
                'name' => $game['name'],
                'thumbnail' => $game['thumbnail'],
                'minplayer' => $game['stats']['@attributes']['minplayers'],
                'maxplayer' => $game['stats']['@attributes']['maxplayers'],
                'playingtime' => isset($game['stats']['@attributes']['playingtime']) ? $game['stats']['@attributes']['playingtime'] : 0
            ];
        }

        $GLOBALS['data']['gamesCollection'] = $arrayGameCollection;
    }

    public static function getRatedRelatedArrays($arrayRawGamesRated)
    {
        $arrayGameRated = [];
        foreach ($arrayRawGamesRated['item'] as $game) {
            if (isset($game['stats']['rating']['@attributes']['value']) && $game['stats']['rating']['@attributes']['value'] != 'N/A') {
                $rating = $game['stats']['rating']['@attributes']['value'];
            } else {
                $rating = 0;
            }
            $thumbnail = isset($game['thumbnail']) ? $game['thumbnail'] : '';
            $minplayers = isset($game['minplayers']) ? $game['minplayers'] : '';
            $maxplayers = isset($game['maxplayers']) ? $game['maxplayers'] : '';

            $arrayGameRated[$game['@attributes']['objectid']] = [
                'name' => $game['name'],
                'thumbnail' => $thumbnail,
                'minplayer' => $minplayers,
                'maxplayer' => $maxplayers,
                'playingtime' => isset($game['stats']['@attributes']['playingtime']) ? $game['stats']['@attributes']['playingtime'] : 0,
                'rating' => $rating
            ];
        }

        $GLOBALS['data']['gamesRated'] = $arrayGameRated;
    }

}