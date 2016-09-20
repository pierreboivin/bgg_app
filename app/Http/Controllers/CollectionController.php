<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lib\BGGData;
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
        $allMechanics = [];

        $arrayGames = [];
        foreach ($GLOBALS['data']['gamesCollection'] as $idGame => $gameProperties) {
            $classes = [];

            $arrayGame = $this->preProcessGameInfo($gameProperties);

            if ($arrayGame['playingtime'] <= 30) {
                $classes[] = '30minus';
            } elseif ($arrayGame['playingtime'] > 30 && $arrayGame['playingtime'] <= 60) {
                $classes[] = '31to60';
            } elseif ($arrayGame['playingtime'] > 61 && $arrayGame['playingtime'] <= 120) {
                $classes[] = '61to120';
            } elseif($arrayGame['playingtime'] > 120) {
                $classes[] = '121plus';
            }

            if(isset($gameProperties['detail']['boardgamemechanic'])) {
                foreach($gameProperties['detail']['boardgamemechanic'] as $mechanic) {
                    $classes[] = Utility::getKeyByString($mechanic['value']);
                    $allMechanics[] = $mechanic['value'];
                }
            }

            if($arrayGame['minplayer'] && $arrayGame['maxplayer']) {
                $begin = (int)$arrayGame['minplayer'];
                $end = (int)$arrayGame['maxplayer'];

                if ($begin == 1) {
                    $classes[] = 'players_solo';
                }
                if ($end >= 7) {
                    $classes[] = 'players_plus';
                }
                for ($i = $begin; $i <= $end; $i++) {
                    $classes[] = 'players_' . $i;

                    if(isset($gameProperties['poll']['process_suggested_numplayers']['best']) && $gameProperties['poll']['process_suggested_numplayers']['best'] == $i) {
                        $classes[] = 'players_' . $i . '_best';
                    }
                    if(isset($gameProperties['poll']['process_suggested_numplayers']['recommended']) && $gameProperties['poll']['process_suggested_numplayers']['recommended'] == $i) {
                        $classes[] = 'players_' . $i . '_recommended';
                    }
                }
            }

            $arrayGame['class'] = implode(' ', $classes);

            $arrayGame['tooltip'] = 'Nombre de parties joués : ' . $arrayGame['numplays'];
            if($arrayGame['minplayer'] > 0 && $arrayGame['maxplayer'] > 0) {
                $arrayGame['tooltip'] .= '<br>Nombre de joueurs : ' . $arrayGame['minplayer'] . ' à ' . $arrayGame['maxplayer'];
            }
            $arrayGame['tooltip'] .= '<br>Durée d\'une partie : ' . $arrayGame['playingtime'] . ' minutes';
            $arrayGame['tooltip'] .= '<br>Évaluation : ' . $gameProperties['rating'] . ' / 10';
            if (isset($gameProperties['privateinfo']['@attributes']['acquisitiondate'])) {
                $arrayGame['tooltip'] .= '<br>Date d\'acquisition : ' . $gameProperties['privateinfo']['@attributes']['acquisitiondate'];
            }

            $arrayGames[$idGame] = $arrayGame;
        }

        $params['games'] = $arrayGames;

        $mechanics = array_values(array_unique($allMechanics));
        foreach($mechanics as $mechanic) {
            $params['mechanics'][Utility::getKeyByString($mechanic)] = $mechanic;
        }
        ksort($params['mechanics']);

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
            $arrayGameDetail['numplays'] = count($GLOBALS['data']['arrayTotalPlays'][$idGame]);
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

    /**
     * @param $gameProperties
     * @param $arrayGame
     * @return array
     */
    public function preProcessGameInfo($gameProperties)
    {
        $arrayGame = $gameProperties;
        $arrayGame['image'] = 'http://' . $gameProperties['thumbnail'];
        $arrayGame['playingtime'] = isset($gameProperties['playingtime']) ? $gameProperties['playingtime'] : 0;
        $arrayGame['minplayer'] = isset($gameProperties['minplayer']) ? $gameProperties['minplayer'] : 0;
        $arrayGame['maxplayer'] = isset($gameProperties['maxplayer']) ? $gameProperties['maxplayer'] : 0;

        if(isset($gameProperties['expansions'])) {
            foreach ($gameProperties['expansions'] as $expansion) {
                if ($expansion['minplayer'] < $arrayGame['minplayer']) {
                    $arrayGame['minplayer'] = $expansion['minplayer'];
                }
                if ($expansion['maxplayer'] > $arrayGame['maxplayer']) {
                    $arrayGame['maxplayer'] = $expansion['maxplayer'];
                }
            }
        }

        if (isset($gameProperties['privateinfo']['@attributes']['acquisitiondate'])) {
            $arrayGame['acquisitiondate'] = $gameProperties['privateinfo']['@attributes']['acquisitiondate'];
        } else {
            $arrayGame['acquisitiondate'] = '0000-00-00';
        }

        return $arrayGame;
    }
}
