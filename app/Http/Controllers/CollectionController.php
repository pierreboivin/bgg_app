<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lib\BGGData;
use App\Lib\Page;
use App\Lib\Stats;
use App\Lib\UserInfos;
use App\Lib\Utility;

class CollectionController extends Controller
{
    public function home()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayGamesDetails = BGGData::getDetailOwned($arrayRawGamesOwned);
        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayUserInfos = UserInfos::getUserInformations($arrayRawUserInfos);
        Stats::getCollectionArrays($arrayRawGamesOwned);
        Stats::getOwnedRelatedArrays($arrayGamesDetails);
        $params['userinfo'] = $arrayUserInfos;
        $allMechanics = [];

        $arrayGames = [];
        foreach ($GLOBALS['data']['gamesCollection'] as $idGame => $gameProperties) {

            $arrayGame = [];
            $classes = [];

            $arrayGame['name'] = $gameProperties['name'];
            $arrayGame['image'] = 'http://' . $gameProperties['thumbnail'];
            $arrayGame['playingtime'] = isset($gameProperties['playingtime']) ? $gameProperties['playingtime'] : 0;
            $arrayGame['minplayer'] = isset($gameProperties['minplayer']) ? $gameProperties['minplayer'] : 0;
            $arrayGame['maxplayer'] = isset($gameProperties['maxplayer']) ? $gameProperties['maxplayer'] : 0;
            $arrayGame['numplays'] = $gameProperties['numplays'];
            $arrayGame['rating'] = $gameProperties['rating'];

            if (isset($gameProperties['privateinfo']['@attributes']['acquisitiondate'])) {
                $arrayGame['acquisitiondate'] = $gameProperties['privateinfo']['@attributes']['acquisitiondate'];
            } else {
                $arrayGame['acquisitiondate'] = '0000-00-00';
            }

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
}
