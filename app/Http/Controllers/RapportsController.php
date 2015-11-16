<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lib\BGGData;
use App\Lib\Page;
use App\Lib\Stats;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class RapportsController extends Controller
{
    public function home()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayUserInfos = \App\Lib\UserInfos::getUserInformations($arrayRawUserInfos);
        $arrayRawGamesPlays = BGGData::getPlays();

        Stats::getPlaysRelatedArrays($arrayRawGamesPlays);

        $params['listMonth'] = [];

        krsort($GLOBALS['data']['arrayPlaysByMonth']);
        foreach($GLOBALS['data']['arrayPlaysByMonth'] as $monthTstamp => $monthStats) {
            $monthYear = Carbon::create(date("Y", $monthTstamp), date("n", $monthTstamp), 1, 0, 0, 0);
            $params['listMonth'][$monthYear->format('Y')][$monthTstamp] = $monthYear->formatLocalized('%B %Y');
        }

        $params['userinfo'] = $arrayUserInfos;

        if (Request::isMethod('post')) {
            $monthSelected = Input::get('month');

            foreach($GLOBALS['data']['arrayPlaysByMonth'][$monthSelected] as $idGame => $gameInfo) {
                $otherInformationsGame = $GLOBALS['data']['arrayTotalPlays'][$idGame];
                $addClass = '';
                if($gameInfo['nbPlayed'] == $otherInformationsGame['nbPlayed']) {
                    $addClass = 'success';
                }
                $dtoGames[$idGame] = ['nbPlayedThisMonth' => $gameInfo['nbPlayed'], 'otherInfo' => $otherInformationsGame, 'addClass' => $addClass];
            }

            uasort($dtoGames, 'self::compareOrder');

            $params['playsThisMonth'] = $dtoGames;

            $params['currentMonth'] = Carbon::create(date("Y", $monthSelected), date("n", $monthSelected), 1, 0, 0, 0)->formatLocalized('%B %Y');
        } else {
            $monthSelected = '';
            $params['playsThisMonth'] = '';
        }

        $params['monthSelected'] = $monthSelected;
        $params = array_merge($paramsMenu, $params);

        return \View::make('rapports', $params);
    }

    private static function compareOrder($a, $b)
    {
        return $b['nbPlayedThisMonth'] - $a['nbPlayedThisMonth'];
    }

}
