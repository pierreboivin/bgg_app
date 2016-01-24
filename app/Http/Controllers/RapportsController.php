<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lib\BGGData;
use App\Lib\Page;
use App\Lib\Stats;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class RapportsController extends Controller
{
    public function mensuel()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        $arrayUserInfos = \App\Lib\UserInfos::getUserInformations($arrayRawUserInfos);
        $arrayRawGamesPlays = BGGData::getPlays();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);
        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);

        $params['listMonth'] = [];

        krsort($GLOBALS['data']['arrayPlaysByMonth']);
        foreach ($GLOBALS['data']['arrayPlaysByMonth'] as $monthTstamp => $monthStats) {
            $monthYear = Carbon::create(date("Y", $monthTstamp), date("n", $monthTstamp), 1, 0, 0, 0);
            $params['listMonth'][$monthYear->format('Y')][$monthTstamp] = $monthYear->formatLocalized('%B %Y');
        }

        $params['userinfo'] = $arrayUserInfos;

        $monthSelected = '';
        $params['playsThisMonth'] = '';
        $params['acquisitionsThisMonth'] = '';
        $newGames = 0;
        $allPlays = 0;

        if (Input::get('month')) {
            $monthSelected = Input::get('month');

            if (isset($GLOBALS['data']['arrayPlaysByMonth'][$monthSelected])) {
                foreach ($GLOBALS['data']['arrayPlaysByMonth'][$monthSelected] as $idGame => $gameInfo) {
                    $otherInformationsGame = $GLOBALS['data']['arrayTotalPlays'][$idGame];
                    $dtoGames[$idGame] = [
                        'nbPlayed' => $gameInfo['nbPlayed'],
                        'otherInfo' => $otherInformationsGame,
                        'newGame' => false
                    ];
                    if ($gameInfo['nbPlayed'] == $otherInformationsGame['nbPlayed']) {
                        $dtoGames[$idGame]['newGame'] = 'true';
                        $newGames++;
                    }
                    $allPlays += $gameInfo['nbPlayed'];
                }

                uasort($dtoGames, 'self::compareOrder');

                $params['playsThisMonth'] = $dtoGames;
            }

            if (isset($GLOBALS['data']['acquisitionsByMonth'][$monthSelected])) {
                $params['acquisitionsThisMonth'] = $GLOBALS['data']['acquisitionsByMonth'][$monthSelected];
            }

            $params['currentMonth'] = Carbon::create(date("Y", $monthSelected), date("n", $monthSelected), 1, 0, 0,
                0)->formatLocalized('%B %Y');
        }

        $params['monthSelected'] = $monthSelected;
        $params['stats'] = ['playNewGames' => $newGames, 'playTotal' => $allPlays];

        $params = array_merge($paramsMenu, $params);

        return \View::make('rapports_mensuel', $params);
    }

    private static function compareOrder($a, $b)
    {
        return $b['nbPlayed'] - $a['nbPlayed'];
    }

    private static function compareOrderRating($a, $b)
    {
        return floatval($b['rating']) > floatval($a['rating']);
    }

    public function annuel()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayUserInfos = \App\Lib\UserInfos::getUserInformations($arrayRawUserInfos);
        $arrayRawGamesPlays = BGGData::getPlays();
        $arrayRawGamesRated = BGGData::getGamesRated();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);
        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);
        Stats::getRatedRelatedArrays($arrayRawGamesRated);
        Stats::getCollectionArrays($arrayRawGamesOwned);

        $allPlays = 0;
        $params['yearSelected'] = '';
        $params['listYear'] = [];
        $params['table'] = [];
        $params['stats'] = [];

        $firstYear = (int) $GLOBALS['data']['firstDatePlayRecorded']->format('Y');

        for($i = $firstYear; $i <= date('Y'); $i++) {
            $params['listYear'][$i] = $i;
        }

        $params['userinfo'] = $arrayUserInfos;

        if (Input::get('year')) {
            $yearSelected = Input::get('year');
            $firstTryGame = 0;

            if (isset($GLOBALS['data']['arrayPlaysByYear'][$yearSelected])) {
                $gamesFirstTryAndRated = [];
                foreach ($GLOBALS['data']['arrayPlaysByYear'][$yearSelected] as $idGame => $gameInfo) {
                    $otherInformationsGame = $GLOBALS['data']['arrayTotalPlays'][$idGame];
                    $dtoGames[$idGame] = [
                        'nbPlayed' => $gameInfo['nbPlayed'],
                        'otherInfo' => $otherInformationsGame
                    ];
                    $allPlays += $gameInfo['nbPlayed'];

                    if(date('Y', $GLOBALS['data']['arrayTotalPlays'][$idGame]['firstPlay']) == $yearSelected) {
                        if(isset($GLOBALS['data']['gamesRated'][$idGame])) {
                            $gamesFirstTryAndRated[$idGame] = $GLOBALS['data']['gamesRated'][$idGame];
                        }
                        $firstTryGame++;
                    }
                }

                uasort($dtoGames, 'self::compareOrder');
                uasort($gamesFirstTryAndRated, 'self::compareOrderRating');

                $dtoGames = array_slice($dtoGames, 0, 30, true);

                $gamesFirstTryAndRated = array_slice($gamesFirstTryAndRated, 0, 20, true);

                $params['table']['firstTryAndGoodRated'] = $gamesFirstTryAndRated;
                $params['table']['mostPlaysThisYear'] = $dtoGames;
                $params['stats']['playTotal'] = $allPlays;
                $params['stats']['percentNewGame'] = round($firstTryGame / count($GLOBALS['data']['arrayPlaysByYear'][$yearSelected]) * 100) . ' % (' . $firstTryGame . ' / ' . count($GLOBALS['data']['arrayPlaysByYear'][$yearSelected]) . ')';
                $params['stats']['playDifferentTotal'] = count($GLOBALS['data']['arrayPlaysByYear'][$yearSelected]);
            }

            $gameCollectionPlayAtLeastOnce = 0;
            foreach ($arrayRawGamesOwned['item'] as $game) {
                $idGameOwned = $game['@attributes']['objectid'];
                if(isset($GLOBALS['data']['arrayPlaysByYear'][$yearSelected][$idGameOwned])) {
                    $gameCollectionPlayAtLeastOnce++;
                }
            }

            $params['stats']['percentGameCollectionPlayed'] = round($gameCollectionPlayAtLeastOnce / count($arrayRawGamesOwned['item']) * 100);


            $params['yearSelected'] = $yearSelected;
        }

        $params = array_merge($paramsMenu, $params);

        return \View::make('rapports_annuel', $params);
    }
}
