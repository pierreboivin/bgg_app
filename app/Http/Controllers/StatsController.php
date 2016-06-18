<?php

namespace App\Http\Controllers;

use App\Lib\BGGData;
use App\Lib\Graphs;
use App\Lib\Page;
use App\Lib\SessionManager;
use App\Lib\Stats;
use App\Lib\UserInfos;
use App\Lib\Utility;

class StatsController extends Controller
{
    public function home()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayGamesDetails = BGGData::getDetailOwned($arrayRawGamesOwned);
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        $arrayRawGamesPlays = BGGData::getPlays();
        $arrayRawGamesRated = BGGData::getGamesRated();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);
        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);
        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getOwnedRelatedArrays($arrayGamesDetails);
        Stats::getRatedRelatedArrays($arrayRawGamesRated);

        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);

        $totalPlayGameCollection = 0;
        foreach($GLOBALS['data']['gamesCollection'] as $idGame => $game) {
            if(isset($GLOBALS['data']['arrayTotalPlays'][$idGame])) {
                $totalPlayGameCollection += $GLOBALS['data']['arrayTotalPlays'][$idGame]['nbPlayed'];
            }
        }

        $params['userinfo'] = $arrayUserInfos;

        $params['stats']['nbGamesOwned'] = $arrayRawGamesOwned['@attributes']['totalitems'];
        $params['stats']['nbGamesAndExpansionsOwned'] = $arrayRawGamesAndExpansionsOwned['@attributes']['totalitems'];
        $params['stats']['nbPlaysTotal'] = $GLOBALS['data']['countAllPlays'];
        $params['stats']['nbPlaysDifferentGame'] = count($GLOBALS['data']['arrayTotalPlays']);
        $params['stats']['averagePlayByMonth'] = 0;
        $params['stats']['averagePlayDifferentByMonth'] = 0;
        if(count($GLOBALS['data']['arrayPlaysByMonth']) > 0) {
            $params['stats']['averagePlayByMonth'] = round($GLOBALS['data']['countAllPlays'] / count($GLOBALS['data']['arrayPlaysByMonth']));
            $params['stats']['averagePlayDifferentByMonth'] = round(count($GLOBALS['data']['arrayTotalPlays']) / count($GLOBALS['data']['arrayPlaysByMonth']));
        }
        $params['stats']['hindex'] = $GLOBALS['data']['hindex'];
        $params['stats']['averageAcquisitionByMonth'] = '';
        if (SessionManager::ifLogin()) {
            if(count($GLOBALS['data']['acquisitionsByMonth']) > 0) {
                $params['stats']['averageAcquisitionByMonth'] = round($GLOBALS['data']['totalWithAcquisitionDate'] / count($GLOBALS['data']['acquisitionsByMonth']));
            }
        }
        $params['stats']['averageValueGames'] = '';
        $params['stats']['totalValueGames'] = '';
        if (SessionManager::ifLogin()) {
            if(count($GLOBALS['data']['arrayValuesGames']) > 0) {
                $params['stats']['averageValueGames'] = Utility::displayMoney($GLOBALS['data']['totalGamesValue'] / count($GLOBALS['data']['arrayValuesGames']));
            }
            $params['stats']['totalValueGames'] = Utility::displayMoney($GLOBALS['data']['totalGamesValue']);
        }

        $params['stats']['nbPlayAveragePlayCollectionGame'] = round($totalPlayGameCollection / count($GLOBALS['data']['gamesCollection']), 2);
        $params['stats']['nbPlayAverageByDay'] = 0;
        $params['stats']['nbPlayDifferentAverageByDay'] = 0;
        if(isset($GLOBALS['data']['nbDaysSinceFirstPlay'])) {
            $params['stats']['nbPlayAverageByDay'] = round($GLOBALS['data']['countAllPlays'] / $GLOBALS['data']['nbDaysSinceFirstPlay'],
                2);
            $params['stats']['nbPlayDifferentAverageByDay'] = round(count($GLOBALS['data']['arrayTotalPlays']) / $GLOBALS['data']['nbDaysSinceFirstPlay'], 2);
        }

        $params['graphs']['byMonth'] = Graphs::getPlayByMonth();
        $params['graphs']['byYear'] = Graphs::getPlayByYear();
        $params['graphs']['byDayWeek'] = Graphs::getPlayByDayWeek();
        $params['graphs']['mostPlayed'] = Graphs::getMostPlayed();
        $params['graphs']['nbPlayer'] = Graphs::getNbPlayerCollection();
        $params['graphs']['acquisitionByMonth'] = Graphs::getAcquisitionByMonth();
        $params['graphs']['mostType'] = Graphs::getMostType();
        $params['graphs']['playsByRating'] = Graphs::getPlayByRating();

        $params['table']['ownedTimePlayed'] = Graphs::getOwnedTimePlayed();
        $params['table']['mostDesigner'] = Graphs::getMostDesignerOwned();
        $params['table']['ownedRentable'] = Graphs::getOwnedRentable();

        $params = array_merge($params, $paramsMenu);

        return \View::make('stats', $params);
    }

    /**
     * @param $type
     * @param $username
     * @param $page
     * @return mixed
     */
    public function ajaxTableTimeSince($type, $username, $page)
    {
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayRawGamesPlays = BGGData::getPlays();
        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);
        Stats::getCollectionArrays($arrayRawGamesOwned);

        $params['table']['ownedTimePlayed'] = Graphs::getOwnedTimePlayed($page);
        $params['type'] = $type;

        return \View::make('partials.lines-table-time-since', $params);
    }

    /**
     * @param $page
     */
    public function ajaxTableRentable($type, $username, $page)
    {
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayRawGamesPlays = BGGData::getPlays();
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);
        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);

        $params['table']['ownedRentable'] = Graphs::getOwnedRentable($page);
        $params['type'] = $type;

        return \View::make('partials.lines-table-rentable', $params);
    }

    /**
     * @param $page
     */
    public function ajaxTableMostRentablePrevious($username, $page)
    {
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayRawGamesPlays = BGGData::getPlays();
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);
        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);

        $params['table']['ownedRentable'] = Graphs::getOwnedRentable($page);

        return \View::make('partials.lines-table-most-rentable', $params);
    }

    /**
     * @param $page
     */
    public function ajaxTableLessRentablePrevious($username, $page)
    {
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayRawGamesPlays = BGGData::getPlays();
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);
        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);

        $params['table']['ownedRentable'] = Graphs::getOwnedRentable($page);

        return \View::make('partials.lines-table-less-rentable', $params);
    }

    /**
     * @param $page
     */
    public function ajaxPlayByYear($username, $page)
    {
        $yearArray = $this->getYearArray($page);

        echo json_encode($yearArray);
    }

    /**
     * @param $page
     * @param $label
     */
    public function ajaxPlayByYearGetUrl($username, $page, $label)
    {
        $yearArray = $this->getYearArray($page);

        echo $yearArray['urls'][$label];
    }

    /**
     * @param $page
     * @return array
     */
    private function getYearArray($page)
    {
        $arrayRawGamesPlays = BGGData::getPlays();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);

        return Graphs::getPlayByYear($page);
    }

    /**
     * @param $page
     */
    public function ajaxPlayByMonth($username, $page)
    {
        $monthArray = $this->getMonthArray($page);

        echo json_encode($monthArray);
    }

    /**
     * @param $page
     * @param $label
     */
    public function ajaxPlayByMonthGetUrl($username, $page, $label)
    {
        $monthArray = $this->getMonthArray($page);

        echo $monthArray['urls'][$label];
    }

    /**
     * @param $page
     * @return array
     */
    private function getMonthArray($page)
    {
        $arrayRawGamesPlays = BGGData::getPlays();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);

        return Graphs::getPlayByMonth($page);
    }

    /**
     * @param $username
     * @param $page
     */
    public function ajaxMostPlayedPrevious($username, $page)
    {
        $mostPlayedArray = $this->getMostPlayedArray($page);

        echo json_encode($mostPlayedArray);
    }

    /**
     * @param $page
     * @param $label
     */
    public function ajaxMostPlayedGetUrl($username, $page, $label)
    {
        $mostPlayedArray = $this->getMostPlayedArray($page);

        echo $mostPlayedArray['urls'][$label];
    }

    /**
     * @param $page
     * @return array
     */
    private function getMostPlayedArray($page)
    {
        $arrayRawGamesPlays = BGGData::getPlays();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);

        $mostPlayed = Graphs::getMostPlayed($page);
        return $mostPlayed;
    }

    /**
     * @param $username
     * @param $page
     */
    public function ajaxMostTypePrevious($username, $page)
    {
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayGamesDetails = BGGData::getDetailOwned($arrayRawGamesOwned);

        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getOwnedRelatedArrays($arrayGamesDetails);

        $mostType = Graphs::getMostType($page);

        echo json_encode($mostType);
    }


    /**
     * @param $username
     * @param $page
     */
    public function ajaxAcquisitionPrevious($username, $page)
    {
        $acquisitionArray = $this->getAcquisitionByMonthArray($page);

        echo json_encode($acquisitionArray);
    }

    /**
     * @param $username
     * @param $page
     * @param $label
     */
    public function ajaxAcquisitionByMonthGetUrl($username, $page, $label)
    {
        $acquisitionArray = $this->getAcquisitionByMonthArray($page);

        echo $acquisitionArray['urls'][$label];
    }

    /**
     * @param $page
     * @return array
     */
    private function getAcquisitionByMonthArray($page)
    {
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();

        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);

        $acquisitionArray = Graphs::getAcquisitionByMonth($page);

        return $acquisitionArray;
    }

}
