<?php

namespace App\Http\Controllers;

use App\Lib\BGGData;
use App\Lib\Page;
use App\Lib\SessionManager;
use App\Lib\UserInfos;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    function __construct()
    {
    }

    public function home()
    {
        $params = [];
        if(SessionManager::ifBggInfo()) {
            $arrayRawUserInfos = BGGData::getUserInfos();

            $arrayBuddies = UserInfos::formatArrayUserInfo($arrayRawUserInfos, 'buddies', 'buddy');
            $arrayGamesTop = UserInfos::formatArrayUserInfo($arrayRawUserInfos, 'top', 'item');
            $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);

            $params['userinfo'] = $arrayUserInfos;
            $params['userinfo']['lists']['buddies'] = $arrayBuddies;
            $params['userinfo']['lists']['topGames'] = $arrayGamesTop;

        }
        $paramsMenu = Page::getMenuParams();

        $params = array_merge($params, $paramsMenu);

        return \View::make('home', $params);
    }

    public function check_loading() {
        return Response::json(BGGData::getCurrentDataInCache());
    }

    public function load() {
        BGGData::getUserInfos();
        $arrayRawGamesOwned = BGGData::getGamesOwned();
        BGGData::getDetailOwned($arrayRawGamesOwned);
        BGGData::getGamesAndExpansionsOwned();
        BGGData::getGamesRated();
        BGGData::getPlays();
    }
}
