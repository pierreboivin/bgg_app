<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lib\BGGData;
use App\Lib\Graphs;
use App\Lib\Page;
use App\Lib\Stats;
use App\Lib\UserInfos;
use App\Lib\Utility;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

class RapportsController extends Controller
{
    public function home()
    {
        return \View::make('rapports');
    }

    public function mensuel()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);
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
        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);
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

        $firstYear = (int)$GLOBALS['data']['firstDatePlayRecorded']->format('Y');

        for ($i = date('Y'); $i >= $firstYear; $i--) {
            $params['listYear'][$i] = $i;
        }

        $params['userinfo'] = $arrayUserInfos;

        if (Input::get('year')) {
            $yearSelected = Input::get('year');
            $firstTryGame = 0;

            if (isset($GLOBALS['data']['arrayPlaysByYear'][$yearSelected])) {
                $gamesFirstTryAndRated = [];
                $gamesCollectionNotPlayed = [];
                foreach ($GLOBALS['data']['arrayPlaysByYear'][$yearSelected] as $idGame => $gameInfo) {
                    $otherInformationsGame = $GLOBALS['data']['arrayTotalPlays'][$idGame];
                    $dtoGames[$idGame] = [
                        'nbPlayed' => $gameInfo['nbPlayed'],
                        'otherInfo' => $otherInformationsGame
                    ];
                    $allPlays += $gameInfo['nbPlayed'];

                    if (date('Y', $GLOBALS['data']['arrayTotalPlays'][$idGame]['firstPlay']) == $yearSelected) {
                        if (isset($GLOBALS['data']['gamesRated'][$idGame])) {
                            $gamesFirstTryAndRated[$idGame] = $GLOBALS['data']['gamesRated'][$idGame];
                        }
                        $firstTryGame++;
                    }
                }
                foreach ($GLOBALS['data']['gamesCollection'] as $idGame => $gameInfo) {
                    if (!isset($GLOBALS['data']['arrayPlaysByYear'][$yearSelected][$idGame])) {
                        $gamesCollectionNotPlayed[$idGame] = $gameInfo;
                        $gamesCollectionNotPlayed[$idGame]['rating'] = '';
                        if (isset($GLOBALS['data']['gamesRated'][$idGame])) {
                            $gamesCollectionNotPlayed[$idGame]['rating'] = $GLOBALS['data']['gamesRated'][$idGame]['rating'];
                        }
                    }
                }

                $nbGameCollectionPlayAtLeastOnce = count($GLOBALS['data']['gamesCollection']) - count($gamesCollectionNotPlayed);

                // First try and rated
                uasort($gamesFirstTryAndRated, 'self::compareOrderRating');
                $gamesFirstTryAndRated = array_slice($gamesFirstTryAndRated, 0, 20, true);

                // Most plays this year
                uasort($dtoGames, 'self::compareOrder');
                $dtoGames = array_slice($dtoGames, 0, 30, true);

                $params['table']['firstTryAndGoodRated'] = $gamesFirstTryAndRated;
                $params['table']['mostPlaysThisYear'] = $dtoGames;
                $params['table']['gameCollectionNotPlayed'] = $gamesCollectionNotPlayed;
                $params['stats']['playTotal'] = $allPlays;
                $params['stats']['percentNewGame'] = round($firstTryGame / count($GLOBALS['data']['arrayPlaysByYear'][$yearSelected]) * 100) . ' % (' . $firstTryGame . ' / ' . count($GLOBALS['data']['arrayPlaysByYear'][$yearSelected]) . ')';
                $params['stats']['playDifferentTotal'] = count($GLOBALS['data']['arrayPlaysByYear'][$yearSelected]);
            }

            $params['stats']['percentGameCollectionPlayed'] = round($nbGameCollectionPlayAtLeastOnce / count($arrayRawGamesOwned['item']) * 100);

            $params['yearSelected'] = $yearSelected;
        }

        $params = array_merge($paramsMenu, $params);

        return \View::make('rapports_annuel', $params);
    }

    public function vendre()
    {
        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);
        $arrayRawGamesPlays = BGGData::getPlays();
        $arrayRawGamesRated = BGGData::getGamesRated();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);
        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);
        Stats::getRatedRelatedArrays($arrayRawGamesRated);
        Stats::getCollectionArrays($arrayRawGamesOwned);

        $params['userinfo'] = $arrayUserInfos;

        $gamesToSell = [];
        $nbToGetInEachCategory = floor(count($GLOBALS['data']['gamesCollection']) / 5);

        // Less rentable
        $arrayRentable = Stats::getRentabiliteCollection();
        $lessRentable = array_reverse(array_slice($arrayRentable, count($arrayRentable) - $nbToGetInEachCategory));
        foreach ($lessRentable as &$game) {
            $game['rentabilite'] = round($game['rentabilite'], 2) . ' $ par partie';
        }
        $this->compileGameArray($gamesToSell, $lessRentable, 'Moins rentable', 1, 'rentabilite');

        // Played since
        $gameLessTimePlayed = Stats::getCollectionTimePlayed();
        $mostTimeSincePlayed = array_slice($gameLessTimePlayed, 0, $nbToGetInEachCategory);
        foreach ($mostTimeSincePlayed as &$game) {
            if (!$game['since']) {
                $game['since'] = 'jamais';
            }
        }
        $this->compileGameArray($gamesToSell, $mostTimeSincePlayed, 'Joué depuis longtemps', 1, 'since');

        // Less played
        $lessPlayed = $GLOBALS['data']['gamesCollection'];
        usort($lessPlayed, function ($a, $b) {
            return $a['numplays'] - $b['numplays'];
        });
        $lessPlayed = array_slice($lessPlayed, 0, $nbToGetInEachCategory);
        foreach ($lessPlayed as &$game) {
            if ($game['numplays'] == 0) {
                $game['numplays'] = 'jamais';
            } else {
                $game['numplays'] . ' parties';
            }
        }
        $this->compileGameArray($gamesToSell, $lessPlayed, 'Moins joués', 1, 'numplays');

        // Less rated
        $arrayRated = Stats::getRatedCollection();
        $lessRated = array_reverse(array_slice($arrayRated, count($arrayRated) - $nbToGetInEachCategory));
        foreach ($lessRated as &$game) {
            $game['rating'] .= ' / 10';
        }
        $this->compileGameArray($gamesToSell, $lessRated, 'Moins bien classé', 1, 'rating');


        usort($gamesToSell, function ($a, $b) {
            return $b['weight'] - $a['weight'];
        });

        $params['games'] = $gamesToSell;

        return \View::make('rapports_vendre', $params);
    }

    public function compileGameArray(&$compilation, $arrayGames, $reason, $weight = 1, $suppField = '')
    {
        foreach ($arrayGames as $game) {
            if ($suppField) {
                $otherInfo = ' (' . $game[$suppField] . ')';
            }
            if (isset($game['weight'])) {
                $weight = $game['weight'];
            }
            if (isset($compilation[$game['id']])) {
                $compilation[$game['id']]['reason'][] = $reason . $otherInfo;
                $compilation[$game['id']]['weight'] += $weight;
            } else {
                $compilation[$game['id']] = [
                    'id' => $game['id'],
                    'name' => $game['name'],
                    'reason' => [$reason . $otherInfo],
                    'weight' => $weight
                ];
            }
        }
    }

    /**
     * @return mixed
     */
    public function tobuy()
    {
        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayGamesDetails = BGGData::getDetailOwned($arrayRawGamesOwned);
        $arrayRawGamesRated = BGGData::getGamesRated();
        $arrayRawGamesPlays = BGGData::getPlays();
        $arrayGamesHot = BGGData::getHotWithDetails();

        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);
        Stats::getRatedRelatedArrays($arrayRawGamesRated);
        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getOwnedRelatedArrays($arrayGamesDetails);
        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);

        $params['userinfo'] = $arrayUserInfos;

        $gamesToBuy = [];

        // High rated
        $highRating = [];
        foreach ($GLOBALS['data']['gamesRated'] as $idGame => $ratedGame) {
            if (!isset($GLOBALS['data']['gamesCollection'][$idGame])) {
                $highRating[$idGame] = $ratedGame;
            }
        }
        uasort($highRating, 'self::compareOrderRating');
        $highRating = array_slice($highRating, 0, 50, true);
        foreach ($highRating as &$game) {
            $game['weight'] = $game['rating'];
            $game['rating'] .= ' / 10';
        }
        Utility::normalizeArray($highRating, 'weight');
        $this->compileGameArray($gamesToBuy, $highRating, 'Bien classé', 1, 'rating');

        // Played frequently
        $playsFrequently = [];
        foreach ($GLOBALS['data']['arrayTotalPlays'] as $idGame => $game) {
            if (!isset($GLOBALS['data']['gamesCollection'][$idGame])) {
                $playsFrequently[$idGame] = $game;
            }
        }
        usort($playsFrequently, function ($a, $b) {
            return $a['nbPlayed'] - $b['nbPlayed'];
        });
        $playsFrequently = array_slice(array_reverse($playsFrequently), 0, 50, true);
        foreach ($playsFrequently as &$game) {
            $game['weight'] = $game['nbPlayed'];
            $game['nbPlayed'] .= ' parties';
        }
        Utility::normalizeArray($playsFrequently, 'weight');
        $this->compileGameArray($gamesToBuy, $playsFrequently, 'Joué souvent', 1, 'nbPlayed');

        // Game hot of the same author
        $gameWithDesignerHot = [];
        $mostDesigner = Graphs::getMostDesignerOwned();
        foreach ($arrayGamesHot as $idGame => $game) {
            if (!isset($GLOBALS['data']['gamesCollection'][$idGame])) {
                if (isset($game['detail']['boardgamedesigner'])) {
                    foreach ($game['detail']['boardgamedesigner'] as $hotDesigner) {
                        if (isset($mostDesigner[$hotDesigner['id']])) {
                            $gameWithDesignerHot[$idGame] = $game;
                            $gameWithDesignerHot[$idGame]['weight'] = $mostDesigner[$hotDesigner['id']]['nbOwned'];
                            $gameWithDesignerHot[$idGame]['designer'] = $mostDesigner[$hotDesigner['id']]['name'];
                        }
                    }
                }
            }
        }
        Utility::normalizeArray($gameWithDesignerHot, 'weight');
        $this->compileGameArray($gamesToBuy, $gameWithDesignerHot, 'Jeu d\'un auteur apprécié', 1, 'designer');

        usort($gamesToBuy, function ($a, $b) {
            return $b['weight'] - $a['weight'];
        });

        $params['games'] = $gamesToBuy;

        return \View::make('rapports_tobuy', $params);
    }

    public function home_compare_user()
    {
        $arrayRawUserInfos = BGGData::getUserInfos();

        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);

        $params['userinfo'] = $arrayUserInfos;

        return \View::make('rapports_home_compare_user', $params);
    }

    public function compare()
    {
        $compareUsername = $_GET['compare'];

        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayRawGamesOwned = BGGData::getGamesOwned();

        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);
        Stats::getCollectionArrays($arrayRawGamesOwned);

        $gamesOwnedOtherUser = BGGData::getGamesOwnedByUsername($compareUsername);
        Stats::getCollectionArrays($gamesOwnedOtherUser, 'gamesCompare');

        $gamesInCommon = array_intersect_key($GLOBALS['data']['gamesCompare'], $GLOBALS['data']['gamesCollection']);

        $similarity = round(count($gamesInCommon)/count($GLOBALS['data']['gamesCollection'])*100, 2);

        $params['compareinfo'] = $compareUsername;
        $params['userinfo'] = $arrayUserInfos;
        $params['gamesInCommon'] = ['correlation' => $similarity, 'games' => $gamesInCommon];

        return \View::make('rapports_compare', $params);
    }

    public function check_loading() {
        return Response::json(BGGData::getCurrentUserNameCollectionDataInCache($_GET['compare']));
    }

    public function loadCompare()
    {
        BGGData::getGamesOwnedByUsername($_GET['compare']);
    }
}
