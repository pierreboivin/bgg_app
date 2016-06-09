<?php

namespace App\Lib;

use Carbon\Carbon;
use Laravelrus\LocalizedCarbon\LocalizedCarbon;

class Graphs
{
    const PLAY_BY_MONTH_SLICE = 13;

    const PLAY_BY_YEAR_SLICE = 10;

    const MOST_PLAYED_SLICE = 20;

    const MOST_TYPE_SLICE = 20;

    const TABLE_TIME_SINCE_PLAY_SLICE = 20;

    const TABLE_OWNED_RENTABLE_SLICE = 20;

    const ACQUISITION_BY_MONTH_SLICE = 13;

    const NB_PLAYER_MAX = 12;

    public static function getPlayByMonth($numPage = 1)
    {
        // Define which plays are new
        $gamesAlreadyPlayed = [];
        foreach ($GLOBALS['data']['arrayPlaysByMonth'] as $dateTstamp => $games) {
            foreach ($games as $gameId => $properties) {
                if (!in_array($gameId, $gamesAlreadyPlayed)) {
                    $gamesAlreadyPlayed[] = $gameId;
                    $GLOBALS['data']['arrayPlaysByMonth'][$dateTstamp][$gameId]['new'] = true;
                } else {
                    $GLOBALS['data']['arrayPlaysByMonth'][$dateTstamp][$gameId]['new'] = false;
                }
            }
        }

        if ((count($GLOBALS['data']['arrayPlaysByMonth']) - (self::PLAY_BY_MONTH_SLICE * $numPage)) < 0) {
            $arraySomeMonths = array_slice($GLOBALS['data']['arrayPlaysByMonth'], 0, self::PLAY_BY_MONTH_SLICE, true);
        } else {
            $arraySomeMonths = array_slice($GLOBALS['data']['arrayPlaysByMonth'],
                count($GLOBALS['data']['arrayPlaysByMonth']) - (self::PLAY_BY_MONTH_SLICE * $numPage),
                self::PLAY_BY_MONTH_SLICE, true);
        }

        // Build differents stats array
        $arrayPlayByMonth = [];
        $arrayPlayDifferentByMonth = [];
        $arrayPlayNewByMonth = [];
        $arrayUrls = [];

        foreach ($arraySomeMonths as $dateTstamp => $games) {
            foreach ($games as $gameId => $properties) {
                $monthYear = ucwords(Carbon::createFromTimestamp($dateTstamp)->formatLocalized('%b %Y'));
                Utility::arrayIncrementValue($arrayPlayByMonth, $monthYear, $properties['nbPlayed']);
                Utility::arrayIncrementValue($arrayPlayDifferentByMonth, $monthYear, 1);
                Utility::arrayIncrementValue($arrayPlayNewByMonth, $monthYear, $properties['new'] ? 1 : 0);

                $dateString = 'start/' . Carbon::createFromTimestamp($dateTstamp)->startOfMonth()->format('Y-m-d') . '/end/' . Carbon::createFromTimestamp($dateTstamp)->endOfMonth()->format('Y-m-d');
                $arrayUrls[$monthYear] = 'http://boardgamegeek.com/plays/bydate/user/' . $GLOBALS['parameters']['general']['username'] . '/subtype/boardgame/' . $dateString;
            }
        }

        // Return labels and series
        return [
            'labels' => array_keys($arrayPlayByMonth),
            'serie1' => array_values($arrayPlayByMonth),
            'serie2' => array_values($arrayPlayDifferentByMonth),
            'serie3' => array_values($arrayPlayNewByMonth),
            'urls' => $arrayUrls
        ];
    }

    public static function getPlayByYear($numPage = 1)
    {
        // Define which plays are new
        $gamesAlreadyPlayed = [];
        foreach ($GLOBALS['data']['arrayPlaysByYear'] as $dateTstamp => $games) {
            foreach ($games as $gameId => $properties) {
                if (!in_array($gameId, $gamesAlreadyPlayed)) {
                    $gamesAlreadyPlayed[] = $gameId;
                    $GLOBALS['data']['arrayPlaysByYear'][$dateTstamp][$gameId]['new'] = true;
                } else {
                    $GLOBALS['data']['arrayPlaysByYear'][$dateTstamp][$gameId]['new'] = false;
                }
            }
        }

        if ((count($GLOBALS['data']['arrayPlaysByYear']) - (self::PLAY_BY_YEAR_SLICE * $numPage)) < 0) {
            $arraySomeYears = array_slice($GLOBALS['data']['arrayPlaysByYear'], 0, self::PLAY_BY_YEAR_SLICE, true);
        } else {
            $arraySomeYears = array_slice($GLOBALS['data']['arrayPlaysByYear'],
                count($GLOBALS['data']['arrayPlaysByYear']) - (self::PLAY_BY_YEAR_SLICE * $numPage),
                self::PLAY_BY_YEAR_SLICE, true);
        }

        // Build differents stats array
        $arrayPlayByYear = [];
        $arrayPlayDifferentByYear = [];
        $arrayPlayNewByYear = [];
        $arrayUrls = [];

        foreach ($arraySomeYears as $year => $games) {
            foreach ($games as $gameId => $properties) {
                Utility::arrayIncrementValue($arrayPlayByYear, $year, $properties['nbPlayed']);
                Utility::arrayIncrementValue($arrayPlayDifferentByYear, $year, 1);
                Utility::arrayIncrementValue($arrayPlayNewByYear, $year, $properties['new'] ? 1 : 0);

                $dateString = 'start/' . Carbon::createFromDate($year)->startOfYear()->format('Y-m-d') . '/end/' . Carbon::createFromDate($year)->endOfYear()->format('Y-m-d');
                $arrayUrls[$year] = 'http://boardgamegeek.com/plays/bydate/user/' . $GLOBALS['parameters']['general']['username'] . '/subtype/boardgame/' . $dateString;
            }
        }

        // Return labels and series
        return [
            'labels' => array_keys($arrayPlayByYear),
            'serie1' => array_values($arrayPlayByYear),
            'serie2' => array_values($arrayPlayDifferentByYear),
            'serie3' => array_values($arrayPlayNewByYear),
            'urls' => $arrayUrls
        ];
    }

    public static function getMostPlayed($numPage = 1)
    {
        $arrayTotalPlays = $GLOBALS['data']['arrayTotalPlays'];
        $numMostPlayed = array_shift($arrayTotalPlays)['nbPlayed'] + 1;

        $arrayMostPlayed = array_slice($GLOBALS['data']['arrayTotalPlays'],
            (self::MOST_PLAYED_SLICE * $numPage) - self::MOST_PLAYED_SLICE,
            self::MOST_PLAYED_SLICE, true);

        $arrayLabels = [];
        $arrayQuantity = [];
        $arrayUrls = [];
        foreach ($arrayMostPlayed as $gameId => $properties) {
            $arrayLabels[] = str_limit(addslashes($properties['name']), 30, '...');
            $arrayQuantity[] = $properties['nbPlayed'];
            $arrayUrls[$properties['name']] = Utility::urlToGame($gameId);
        }

        return [
            'labels' => array_values($arrayLabels),
            'serie1' => array_values($arrayQuantity),
            'scaleMax' => $numMostPlayed,
            'urls' => $arrayUrls
        ];
    }

    public static function getMostType($numPage = 1)
    {
        $mechanicsNumber = [];
        foreach ($GLOBALS['data']['gamesCollection'] as $gameId => $gameProperties) {
            if (isset($gameProperties['detail']['boardgamemechanic'])) {
                foreach ($gameProperties['detail']['boardgamemechanic'] as $mechanic) {
                    Utility::arrayIncrementValue($mechanicsNumber, $mechanic['value'], 1);
                }
            }
        }

        arsort($mechanicsNumber);

        $mechanicsNumber = array_slice($mechanicsNumber,
            (self::MOST_TYPE_SLICE * $numPage) - self::MOST_TYPE_SLICE,
            self::MOST_TYPE_SLICE, true);

        return [
            'labels' => array_keys($mechanicsNumber),
            'serie1' => array_values($mechanicsNumber)
        ];
    }

    public static function getOwnedTimePlayed($page = 1)
    {
        $gameLessTimePlayed = [];
        foreach ($GLOBALS['data']['gamesCollection'] as $gameId => $gameProperties) {
            if (isset($GLOBALS['data']['arrayTotalPlays'][$gameId])) {
                $gamePlayed = $GLOBALS['data']['arrayTotalPlays'][$gameId]['plays'];

                usort($gamePlayed, function ($a, $b) {
                    return $a['date'] - $b['date'];
                });

                $dateTimestamp = end($gamePlayed)['date'];

                $gameLessTimePlayed[] = [
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

        return [
            'most' => array_slice($gameLessTimePlayed, 0, self::TABLE_TIME_SINCE_PLAY_SLICE * $page),
            'less' => array_reverse(array_slice($gameLessTimePlayed,
                count($gameLessTimePlayed) - self::TABLE_TIME_SINCE_PLAY_SLICE * $page))
        ];
    }

    public static function getNbPlayerCollection()
    {
        $arrayByNbPlayer = [];
        foreach ($GLOBALS['data']['gamesCollection'] as $gameId => $gameProperties) {
            for ($i = intval($gameProperties['minplayer']); $i <= intval($gameProperties['maxplayer']) && $i <= self::NB_PLAYER_MAX; $i++) {
                Utility::arrayIncrementValue($arrayByNbPlayer, $i, 1);
            }
        }

        ksort($arrayByNbPlayer);

        $arrayNbPlayer = [];
        foreach ($arrayByNbPlayer as $label => $value) {
            if ($label == 1) {
                $arrayNbPlayer['solo'] = $value;
            } else {
                $arrayNbPlayer[$label . ' joueurs'] = $value;
            }
        }

        return [
            'labels' => Utility::implodeWrap(array_keys($arrayNbPlayer)),
            'serie1' => Utility::implodeWrap(array_values($arrayNbPlayer))
        ];
    }

    public static function getAcquisitionByMonth($numPage = 1)
    {
        if ((count($GLOBALS['data']['acquisitionsByMonth']) - (self::ACQUISITION_BY_MONTH_SLICE * $numPage)) < 0) {
            $arraySomeAcquisition = array_slice($GLOBALS['data']['acquisitionsByMonth'], 0,
                self::ACQUISITION_BY_MONTH_SLICE, true);
        } else {
            $arraySomeAcquisition = array_slice($GLOBALS['data']['acquisitionsByMonth'],
                count($GLOBALS['data']['acquisitionsByMonth']) - (self::ACQUISITION_BY_MONTH_SLICE * $numPage),
                self::ACQUISITION_BY_MONTH_SLICE, true);
        }

        $acquisitionByMonth = [];
        $arrayUrls = [];
        foreach ($arraySomeAcquisition as $dateTstamp => $arrayGames) {
            $date = Carbon::createFromTimestamp($dateTstamp);
            $label = ucwords($date->formatLocalized('%b %Y'));
            $acquisitionByMonth[$label] = count($arrayGames);
            $arrayUrls[$label] = 'http://boardgamegeek.com/collection/user/' . $GLOBALS['parameters']['general']['username'] . '?sort=acquisitiondate&sortdir=desc&rankobjecttype=subtype&rankobjectid=1&columns=title%7Cstatus%7Cversion%7Cacquisitiondate%7Crating%7Cbggrating&geekranks=%0A%09%09%09%09%09%09%09%09%09Board+Game+Rank%0A%09%09%09%09%09%09%09%09&own=1&ff=1&subtype=boardgame&mindate=' . $date->firstOfMonth()->format('Y-m-d') . '&dateinput=' . $date->lastOfMonth()->format('Y-m-d') . '&maxdate=' . $date->lastOfMonth()->format('Y-m-d');
        }

        return [
            'labels' => array_keys($acquisitionByMonth),
            'serie1' => array_values($acquisitionByMonth),
            'urls' => $arrayUrls
        ];
    }

    public static function getPlayByDayWeek()
    {
        // Build differents stats array
        $arrayPlayByDayWeek = [];

        foreach ($GLOBALS['data']['arrayPlaysByDayWeek'] as $dayWeek => $nbPlay) {
            $labelWeekDay = Carbon::createFromTimestamp(strtotime("Sunday +{$dayWeek} days"))->formatLocalized('%A');
            $arrayPlayByDayWeek[$labelWeekDay] = $nbPlay;
        }

        // Return labels and series
        return [
            'labels' => Utility::implodeWrap(array_keys($arrayPlayByDayWeek)),
            'serie1' => Utility::implodeWrap(array_values($arrayPlayByDayWeek))
        ];
    }

    private static function compareOwned($a, $b)
    {
        return $b['nbOwned'] - $a['nbOwned'];
    }

    public static function getMostDesignerOwned()
    {
        $arrayDesignerFrequency = [];

        foreach ($GLOBALS['data']['gamesCollection'] as $gameId => $game) {
            if (isset($game['detail']['boardgamedesigner'])) {
                $designerArray = $game['detail']['boardgamedesigner'];
                foreach ($designerArray as $designer) {
                    if ($designer['value'] != '(Uncredited)') {
                        $arrayDesignerFrequency[$designer['id']]['name'] = $designer['value'];
                        $arrayDesignerFrequency[$designer['id']]['games'][$gameId] = $game['name'];

                        Utility::arrayIncrementValue($arrayDesignerFrequency, $designer['id'], 1, 'nbOwned');
                    }
                }
            }
        }

        uasort($arrayDesignerFrequency, 'self::compareOwned');
        return array_slice($arrayDesignerFrequency, 0, 20, true);
    }

    private static function compareRentabilite($a, $b)
    {
        return $b['rentabilite'] < $a['rentabilite'];
    }

    public static function getOwnedRentable($numPage = 1)
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
                    'value' => $gameValue,
                    'rentabilite' => $rentabilite,
                    'name' => $game['name'],
                    'numplays' => intval($game['numplays']),
                    'url' => Utility::urlToGame($gameId)
                ];
            }
        }

        uasort($arrayRentable, 'self::compareRentabilite');

        return [
            'most' => array_slice($arrayRentable, 0, self::TABLE_OWNED_RENTABLE_SLICE * $numPage),
            'less' => array_reverse(array_slice($arrayRentable,
                count($arrayRentable) - self::TABLE_OWNED_RENTABLE_SLICE * $numPage))
             ];
    }

}