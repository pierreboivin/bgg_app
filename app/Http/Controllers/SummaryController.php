<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lib\BGGData;
use App\Lib\Graphs;
use App\Lib\Page;
use App\Lib\SessionManager;
use App\Lib\Stats;
use App\Lib\UserInfos;
use App\Lib\Utility;
use Carbon\Carbon;

class SummaryController extends Controller
{
    const TABLE_NB_LAST_PLAY = 10;
    const TABLE_NB_LAST_ACQUISITION = 10;

    public function home()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayGamesDetails = BGGData::getDetailOwned($arrayRawGamesOwned);
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();
        $arrayRawGamesPlays = BGGData::getPlays();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);
        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getOwnedRelatedArrays($arrayGamesDetails);
        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);

        $params['lastPlayed'] = $this->getLastPlays();

        if(SessionManager::ifLoginAsSelf()) {
            $params['lastAcquisition'] = $this->getLastAcquisition();
        } else {
            $params['lastAcquisition'] = [];
        }

        $params['table']['ownedTimePlayed'] = Graphs::getOwnedTimePlayed();

        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);

        $params['userinfo'] = $arrayUserInfos;

        $params = array_merge($params, $paramsMenu);

        return \View::make('summary', $params);
    }

    public function ajaxTableLastPlay($username, $page)
    {
        $arrayRawGamesPlays = BGGData::getPlays();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);

        $params['lastPlayed'] = $this->getLastPlays($page);
        $params['userinfo'] = UserInfos::getUserInformations(BGGData::getUserInfos());

        return \View::make('partials.lines-table-last-games-played', $params);
    }

    public function ajaxTableLastAcquisition($username, $page)
    {
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayGamesDetails = BGGData::getDetailOwned($arrayRawGamesOwned);
        $arrayRawGamesAndExpansionsOwned = BGGData::getGamesAndExpansionsOwned();

        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getOwnedRelatedArrays($arrayGamesDetails);
        Stats::getAcquisitionRelatedArrays($arrayRawGamesAndExpansionsOwned);

        $params['lastAcquisition'] = $this->getLastAcquisition($page);
        $params['userinfo'] = UserInfos::getUserInformations(BGGData::getUserInfos());

        return \View::make('partials.lines-table-last-games-acquisition', $params);
    }

    public function getLastPlays($page = 1)
    {
        $arrayGames = [];
        foreach ($GLOBALS['data']['arrayPlaysByDate'] as $datePlay => $games) {
            foreach ($games as $idGame => $gameInfo) {
                $arrayGames[] = ['id' => $idGame, 'date' => $datePlay];
            }
        }
        $arrayPlays = array_reverse(array_slice($arrayGames, count($arrayGames) - self::TABLE_NB_LAST_PLAY * $page));
        $dtoGames = [];
        foreach ($arrayPlays as $play) {
            $gameInfo = $GLOBALS['data']['arrayTotalPlays'][$play['id']];

            $gameInfo['dateFormated'] = Carbon::createFromTimestamp($play['date'])->formatLocalized('%e %b %Y');
            $gameInfo['since'] = Carbon::createFromTimestamp($play['date'])->diffForHumans();

            $dtoGames[] = $gameInfo;
        }
        return $dtoGames;
    }

    private function getLastAcquisition($page = 1)
    {
        $arrayGames = [];
        foreach ($GLOBALS['data']['acquisitionsByDay'] as $dateAcquisition => $games) {
            foreach ($games as $idGame => $name) {
                $arrayGames[] = ['id' => $idGame, 'name' => $name, 'date' => $dateAcquisition];
            }
        }
        $arrayAcquisition = array_reverse(array_slice($arrayGames, count($arrayGames) - self::TABLE_NB_LAST_ACQUISITION * $page));
        $dtoGames = [];
        foreach ($arrayAcquisition as $acquisition) {
            $acquisition['dateFormated'] = Carbon::createFromTimestamp($acquisition['date'])->formatLocalized('%e %b %Y');
            $acquisition['since'] = Carbon::createFromTimestamp($acquisition['date'])->diffForHumans();

            $dtoGames[] = $acquisition;
        }
        return $dtoGames;
    }
}
