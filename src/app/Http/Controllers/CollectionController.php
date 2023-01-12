<?php

namespace App\Http\Controllers;

use App\Lib\BGGData;
use App\Lib\GamesList;
use App\Lib\Page;
use App\Lib\Stats;
use App\Lib\UserInfos;
use App\Lib\Utility;
use Carbon\Carbon;

class CollectionController extends Controller
{
    public function home()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayGamesDetails = BGGData::getDetailOwned($arrayRawGamesOwned);
        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getOwnedRelatedArrays($arrayGamesDetails);
        Stats::getOwnedExpansionLink($arrayRawGamesAndExpansionsOwned);

        $params['userinfo'] = $arrayUserInfos;

        $params = array_merge($params, GamesList::processGameList($GLOBALS['data']['gamesCollection']));

        $params = array_merge($params, $paramsMenu);

        return \View::make('collection', $params);
    }

    public function game($username, $idGame)
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);

        $params['userinfo'] = $arrayUserInfos;

        $arrayGameDetail = BGGData::getDetailOfGame($idGame);
        $arrayGameDetail = Stats::convertBggDetailInfo($arrayGameDetail);
        $arrayGameDetail = array_merge($arrayGameDetail, Stats::getDetailInfoGame($arrayGameDetail));

        $arrayRawGamesPlays = BGGData::getPlays();
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayGamesDetails = BGGData::getDetailOwned($arrayRawGamesOwned);
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getOwnedRelatedArrays($arrayGamesDetails);
        Stats::getOwnedExpansionLink($arrayRawGamesAndExpansionsOwned);
        if(isset($GLOBALS['data']['gamesCollection'][$idGame])) {
            $arrayGameDetail['collection'] = $GLOBALS['data']['gamesCollection'][$idGame];
        }
        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);

        if(isset($GLOBALS['data']['arrayTotalPlays'][$idGame])) {
            $arrayGameDetail['numplays'] = $GLOBALS['data']['arrayTotalPlays'][$idGame]['nbPlayed'];
            $allPlays = $GLOBALS['data']['arrayTotalPlays'][$idGame]['plays'];
            if ($allPlays) {
                uasort($allPlays, 'App\Lib\Utility::compareDate');
                $arrayGameDetail['lastPlayed']['date'] = $allPlays[0]['date'];
                $arrayGameDetail['lastPlayed']['since'] = Carbon::createFromTimestamp($allPlays[0]['date'])->diffForHumans();
                $arrayGameDetail['plays'] = $allPlays;
            } else {
                $arrayGameDetail['lastPlayed'] = '';
            }
        } else {
            $arrayGameDetail['numplays'] = 0;
            $arrayGameDetail['lastPlayed'] = '';
            $arrayGameDetail['plays'] = [];
        }

        $params['game'] = $arrayGameDetail;

        $params = array_merge($params, $paramsMenu);

        return \View::make('game', $params);
    }

}
