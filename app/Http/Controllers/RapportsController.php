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
    public function home()
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
                        'nbPlayedThisMonth' => $gameInfo['nbPlayed'],
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

        return \View::make('rapports', $params);
    }

    private static function compareOrder($a, $b)
    {
        return $b['nbPlayedThisMonth'] - $a['nbPlayedThisMonth'];
    }

}
