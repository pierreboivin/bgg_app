<?php

namespace App\Lib;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;

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

    /**
     * @param $arrayGamesDetails
     */
    public static function getOwnedRelatedArrays($arrayGamesDetails)
    {
        foreach($GLOBALS['data']['gamesCollection'] as $gameId => $game) {
            $gameDetail = $arrayGamesDetails[$gameId];
            foreach($gameDetail['link'] as $link) {
                $attributes = $link['@attributes'];
                $label = $attributes['value'];
                if (Lang::has('mechanics.' . $attributes['value'])) {
                    $label = trans('mechanics.' . $attributes['value']);
                }
                $GLOBALS['data']['gamesCollection'][$gameId]['detail'][$attributes['type']][] = ['value' => $label, 'id' => $attributes['id']];
            }
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
                } else {
                    $gameValue = 0;
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

    /**
     * @param $arrayTotalPlays
     * @return int
     */
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

    /**
     * @param $arrayRawGamesOwned
     */
    public static function getCollectionArrays($arrayRawGamesOwned)
    {
        $arrayGameCollection = [];
        foreach ($arrayRawGamesOwned['item'] as $game) {
            $arrayGameCollection[$game['@attributes']['objectid']] = [
                'id' => $game['@attributes']['objectid'],
                'name' => $game['name'],
                'thumbnail' => $game['thumbnail'],
                'minplayer' => isset($game['stats']['@attributes']['minplayers']) ? $game['stats']['@attributes']['minplayers'] : 0,
                'maxplayer' => isset($game['stats']['@attributes']['maxplayers']) ? $game['stats']['@attributes']['maxplayers'] : 0,
                'playingtime' => isset($game['stats']['@attributes']['playingtime']) ? $game['stats']['@attributes']['playingtime'] : 0,
                'numplays' => isset($game['numplays']) ? $game['numplays'] : 0
            ];
            if (isset($game['stats']['rating']['@attributes']['value']) && $game['stats']['rating']['@attributes']['value'] != 'N/A') {
                $rating = $game['stats']['rating']['@attributes']['value'];
            } else {
                $rating = 0;
            }
            $arrayGameCollection[$game['@attributes']['objectid']]['rating'] = $rating;
            if(isset($game['privateinfo'])) {
                $arrayGameCollection[$game['@attributes']['objectid']]['private'] = $game['privateinfo'];
            }
        }

        $GLOBALS['data']['gamesCollection'] = $arrayGameCollection;
    }

    /**
     * @param $arrayRawGamesRated
     */
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

    private static function compareRentabilite($a, $b)
    {
        return $b['rentabilite'] < $a['rentabilite'];
    }

    public static function getRentabiliteCollection()
    {
        $arrayRentable = [];
        foreach ($GLOBALS['data']['gamesCollection'] as $gameId => $game) {

            if (isset($GLOBALS['data']['arrayValuesGames'][$gameId]) && $GLOBALS['data']['arrayValuesGames'][$gameId] > 0) {

                $gameValue = $GLOBALS['data']['arrayValuesGames'][$gameId];

                if (intval($game['numplays']) > 0) {
                    $rentabilite = $gameValue / intval($game['numplays']);
                } else {
                    $rentabilite = $gameValue;
                }

                $arrayRentable[$gameId] = [
                    'id' => $gameId,
                    'value' => $gameValue,
                    'rentabilite' => $rentabilite,
                    'name' => $game['name'],
                    'numplays' => intval($game['numplays']),
                    'url' => Utility::urlToGame($gameId)
                ];
            }
        }

        if($arrayRentable) {
            uasort($arrayRentable, 'self::compareRentabilite');
        }

        return $arrayRentable;
    }

    public static function getRatedCollection()
    {
        foreach ($GLOBALS['data']['gamesCollection'] as $gameId => $game) {

            if (isset($GLOBALS['data']['gamesRated'][$gameId])) {

                $arrayRated[$gameId] = [
                    'id' => $gameId,
                    'rating' => $GLOBALS['data']['gamesRated'][$gameId]['rating'],
                    'name' => $game['name'],
                    'url' => Utility::urlToGame($gameId)
                ];
            }
        }

        usort($arrayRated, function ($a, $b) {
            return $b['rating'] - $a['rating'];
        });

        return $arrayRated;
    }

    public static function getCollectionTimePlayed()
    {
        foreach ($GLOBALS['data']['gamesCollection'] as $gameId => $gameProperties) {
            if (isset($GLOBALS['data']['arrayTotalPlays'][$gameId])) {
                $gamePlayed = $GLOBALS['data']['arrayTotalPlays'][$gameId]['plays'];

                usort($gamePlayed, function ($a, $b) {
                    return $a['date'] - $b['date'];
                });

                $dateTimestamp = end($gamePlayed)['date'];

                $gameLessTimePlayed[] = [
                    'id' => $gameId,
                    'name' => $gameProperties['name'],
                    'url' => Utility::urlToGame($gameId),
                    'totalPlays' => count($gamePlayed),
                    'date' => $dateTimestamp,
                    'dateFormated' => Carbon::createFromTimestamp($dateTimestamp)->formatLocalized('%e %b %Y'),
                    'since' => Carbon::createFromTimestamp($dateTimestamp)->diffForHumans()
                ];

            } else {
                // Never played this game
                $gameLessTimePlayed[] = [
                    'id' => $gameId,
                    'name' => $gameProperties['name'],
                    'url' => Utility::urlToGame($gameId),
                    'totalPlays' => 0,
                    'date' => '',
                    'dateFormated' => '',
                    'since' => ''
                ];
            }
        }

        usort($gameLessTimePlayed, function ($a, $b) {
            return $a['date'] - $b['date'];
        });
        return $gameLessTimePlayed;
    }

}