<?php

namespace App\Http\Controllers;

use App\Lib\BGGData;
use App\Lib\Page;

class HomeController extends Controller
{
    function __construct()
    {
    }

    public function home()
    {
        $arrayRawUserInfos = BGGData::getUserInfos();

        $arrayBuddies = \App\Lib\UserInfos::formatArrayUserInfo($arrayRawUserInfos, 'buddies', 'buddy');
        $arrayGamesTop = \App\Lib\UserInfos::formatArrayUserInfo($arrayRawUserInfos, 'top', 'item');
        $arrayUserInfos = \App\Lib\UserInfos::getUserInformations($arrayRawUserInfos);

        $params['userinfo'] = $arrayUserInfos;
        $params['userinfo']['lists']['buddies'] = $arrayBuddies;
        $params['userinfo']['lists']['topGames'] = $arrayGamesTop;

        $paramsMenu = Page::getMenuParams();

        $params = array_merge($params, $paramsMenu);

        return \View::make('home', $params);
    }

}
