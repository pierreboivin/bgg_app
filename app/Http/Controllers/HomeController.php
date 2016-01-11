<?php

namespace App\Http\Controllers;

use App\Lib\BGGData;
use App\Lib\Page;
use App\Lib\UserInfos;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    function __construct()
    {
    }

    public function home()
    {
        $arrayRawUserInfos = BGGData::getUserInfos();

        $arrayBuddies = UserInfos::formatArrayUserInfo($arrayRawUserInfos, 'buddies', 'buddy');
        $arrayGamesTop = UserInfos::formatArrayUserInfo($arrayRawUserInfos, 'top', 'item');
        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);

        $params['userinfo'] = $arrayUserInfos;
        $params['userinfo']['lists']['buddies'] = $arrayBuddies;
        $params['userinfo']['lists']['topGames'] = $arrayGamesTop;

        $paramsMenu = Page::getMenuParams();

        $params = array_merge($params, $paramsMenu);

        return \View::make('home', $params);
    }

    public function check_loading() {
        $progression = BGGData::getCurrentDataInCache();

        return Response::json(array(
            'progress' => $progression,
        ));
    }

    public function load() {
        BGGData::getUserInfos();
        BGGData::getGamesOwned();
        BGGData::getGamesAndExpansionsOwned();
        BGGData::getPlays();
    }

}
