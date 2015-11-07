<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lib\BGGData;
use App\Lib\Page;
use App\Lib\SessionManager;

class CollectionController extends Controller
{
    public function home()
    {
        $paramsMenu = Page::getMenuParams();

        $arrayRawGamesOwned = BGGData::getGamesOwned();
        $arrayRawUserInfos = BGGData::getUserInfos();
        $arrayUserInfos = \App\Lib\UserInfos::getUserInformations($arrayRawUserInfos);
        $params['userinfo'] = $arrayUserInfos;

        $arrayGames = [];
        foreach($arrayRawGamesOwned['item'] as $gameProperties) {

            $arrayGame = [];
            $classes = [];

            $idGame = $gameProperties['@attributes']['objectid'];

            $arrayGame['name'] = $gameProperties['name'];
            $arrayGame['image'] = 'http://' . $gameProperties['thumbnail'];
            $arrayGame['playingtime'] = isset($gameProperties['stats']['@attributes']['playingtime']) ? $gameProperties['stats']['@attributes']['playingtime'] : 0;
            $arrayGame['minplayers'] = isset($gameProperties['stats']['@attributes']['minplayers']) ? $gameProperties['stats']['@attributes']['minplayers'] : 0;
            $arrayGame['maxplayers'] = isset($gameProperties['stats']['@attributes']['maxplayers']) ? $gameProperties['stats']['@attributes']['maxplayers'] : 0;
            $arrayGame['numplays'] = $gameProperties['numplays'];
            $arrayGame['rating'] = $gameProperties['stats']['rating']['@attributes']['value'];

            if($arrayGame['playingtime'] >= 60) {
                $classes[] = 'longgame';
            } elseif($arrayGame['playingtime'] <= 30) {
                $classes[] = 'shortgame';
            }

            $arrayGame['class'] = implode(' ', $classes);

            $arrayGame['tooltip'] = 'Nb partie joué : ' . $arrayGame['numplays'];
            $arrayGame['tooltip'] .= '<br>Durée d\'une partie : ' . $arrayGame['playingtime'] . ' minutes';
            $arrayGame['tooltip'] .= '<br>Classification : ' . $arrayGame['rating'] . ' / 10';

            $arrayGames[$idGame] = $arrayGame;
        }

        $params['games'] = $arrayGames;

        $params = array_merge($params, $paramsMenu);

        return \View::make('collection', $params);
    }
}
