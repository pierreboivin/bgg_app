<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lib\BGGData;
use App\Lib\Page;

class CollectionController extends Controller
{
    public function home()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayGamesDetails = BGGData::getDetailOwned($arrayRawGamesOwned);
        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayUserInfos = \App\Lib\UserInfos::getUserInformations($arrayRawUserInfos);
        $params['userinfo'] = $arrayUserInfos;
        $allMechanics = [];

        $arrayGames = [];
        foreach ($arrayRawGamesOwned['item'] as $gameProperties) {

            $arrayGame = [];
            $classes = [];
            $mechanics = [];

            $idGame = $gameProperties['@attributes']['objectid'];

            $gameDetails = $arrayGamesDetails[$idGame];

            $arrayGame['name'] = $gameProperties['name'];
            $arrayGame['image'] = 'http://' . $gameProperties['thumbnail'];
            $arrayGame['playingtime'] = isset($gameProperties['stats']['@attributes']['playingtime']) ? $gameProperties['stats']['@attributes']['playingtime'] : 0;
            $arrayGame['minplayers'] = isset($gameProperties['stats']['@attributes']['minplayers']) ? $gameProperties['stats']['@attributes']['minplayers'] : 0;
            $arrayGame['maxplayers'] = isset($gameProperties['stats']['@attributes']['maxplayers']) ? $gameProperties['stats']['@attributes']['maxplayers'] : 0;
            $arrayGame['numplays'] = $gameProperties['numplays'];
            if (isset($gameProperties['stats']['rating']['@attributes']['value']) && $gameProperties['stats']['rating']['@attributes']['value'] != 'N/A') {
                $arrayGame['rating'] = $gameProperties['stats']['rating']['@attributes']['value'];
            } else {
                $arrayGame['rating'] = 0;
            }
            if (isset($gameProperties['privateinfo']['@attributes']['acquisitiondate'])) {
                $arrayGame['acquisitiondate'] = $gameProperties['privateinfo']['@attributes']['acquisitiondate'];
            } else {
                $arrayGame['acquisitiondate'] = '0000-00-00';
            }

            if ($arrayGame['playingtime'] >= 60) {
                $classes[] = 'longgame';
            } elseif ($arrayGame['playingtime'] <= 30) {
                $classes[] = 'shortgame';
            }

            if($gameDetails['link']) {
                foreach($gameDetails['link'] as $link) {
                    $attributes = $link['@attributes'];
                    if($link['@attributes']['type'] == 'boardgamemechanic') {
                        $classes[] = str_slug($attributes['value']);
                        $allMechanics[] = $attributes['value'];
                    }
                }
            }

            if($arrayGame['minplayers'] && $arrayGame['maxplayers']) {
                $begin = (int)$arrayGame['minplayers'];
                $end = (int)$arrayGame['maxplayers'];

                if ($begin == 1) {
                    $classes[] = 'players_solo';
                }
                if ($end >= 7) {
                    $classes[] = 'players_plus';
                }
                for ($i = $begin; $i <= $end; $i++) {
                    $classes[] = 'players_' . $i;
                }
            }

            $arrayGame['class'] = implode(' ', $classes);

            $arrayGame['tooltip'] = 'Nombre de parties joués : ' . $arrayGame['numplays'];
            if($arrayGame['minplayers'] > 0 && $arrayGame['maxplayers'] > 0) {
                $arrayGame['tooltip'] .= '<br>Nombre de joueurs : ' . $arrayGame['minplayers'] . ' à ' . $arrayGame['maxplayers'];
            }
            $arrayGame['tooltip'] .= '<br>Durée d\'une partie : ' . $arrayGame['playingtime'] . ' minutes';
            $arrayGame['tooltip'] .= '<br>Évaluation : ' . $gameProperties['stats']['rating']['@attributes']['value'] . ' / 10';
            if (isset($gameProperties['privateinfo']['@attributes']['acquisitiondate'])) {
                $arrayGame['tooltip'] .= '<br>Date d\'acquisition : ' . $gameProperties['privateinfo']['@attributes']['acquisitiondate'];
            }

            $arrayGames[$idGame] = $arrayGame;
        }

        $params['games'] = $arrayGames;

        $mechanics = array_values(array_unique($allMechanics));
        foreach($mechanics as $mechanic) {
            $params['mechanics'][str_slug($mechanic)] = $mechanic;
        }
        asort($params['mechanics']);

        $params = array_merge($params, $paramsMenu);

        return \View::make('collection', $params);
    }
}
