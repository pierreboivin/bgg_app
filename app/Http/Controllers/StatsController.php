<?php

namespace App\Http\Controllers;

use App\Lib\BGGData;
use App\Lib\Graphs;
use App\Lib\Page;
use App\Lib\SessionManager;
use App\Lib\Stats;

class StatsController extends Controller
{
    public function home()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        $arrayRawGamesPlays = BGGData::getPlays();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);
        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);
        Stats::getCollectionArrays($arrayRawGamesOwned);

        $arrayUserInfos = \App\Lib\UserInfos::getUserInformations($arrayRawUserInfos);

        $params['userinfo'] = $arrayUserInfos;

        $params['stats']['nbGamesOwned'] = $arrayRawGamesOwned['@attributes']['totalitems'];
        $params['stats']['nbGamesAndExpansionsOwned'] = $arrayRawGamesAndExpansionsOwned['@attributes']['totalitems'];
        $params['stats']['nbPlaysTotal'] = $GLOBALS['data']['countAllPlays'];
        $params['stats']['nbPlaysDifferentGame'] = count($GLOBALS['data']['arrayTotalPlays']);
        $params['stats']['averagePlayByMonth'] = round($GLOBALS['data']['countAllPlays'] / count($GLOBALS['data']['arrayPlaysByMonth']));
        $params['stats']['hindex'] = $GLOBALS['data']['hindex'];
        if (SessionManager::ifLogin()) {
            $params['stats']['averageAcquisitionByMonth'] = round($GLOBALS['data']['totalWithAcquisitionDate'] / count($GLOBALS['data']['acquisitionsByMonth']));
        } else {
            $params['stats']['averageAcquisitionByMonth'] = '';
        }
        if (SessionManager::ifLogin()) {
            $params['stats']['averageValueGames'] = \App\Lib\Utility::displayMoney($GLOBALS['data']['totalGamesValue'] / count($GLOBALS['data']['arrayValuesGames']));
            $params['stats']['totalValueGames'] = \App\Lib\Utility::displayMoney($GLOBALS['data']['totalGamesValue']);
        } else {
            $params['stats']['averageValueGames'] = '';
            $params['stats']['totalValueGames'] = '';
        }

        $params['graphs']['byMonth'] = Graphs::getPlayByMonth();
        $params['graphs']['byDayWeek'] = Graphs::getPlayByDayWeek();
        $params['graphs']['mostPlayed'] = Graphs::getMostPlayed();
        $params['graphs']['ownedTimePlayed'] = Graphs::getOwnedTimePlayed();
        $params['graphs']['nbPlayer'] = Graphs::getNbPlayerCollection();
        $params['graphs']['acquisitionByMonth'] = Graphs::getAcquisitionByMonth();

        $params = array_merge($params, $paramsMenu);

        return \View::make('stats', $params);
    }

    /**
     * @param $page
     */
    public function ajaxPlayByMonthPrevious($username, $page)
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
