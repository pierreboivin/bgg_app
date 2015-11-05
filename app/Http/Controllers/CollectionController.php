<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lib\BGGData;
use App\Lib\Page;
use App\Lib\SessionManager;

class CollectionController extends Controller
{
    public function home($username = '')
    {
        SessionManager::guestConnexion($username);
        $paramsMenu = Page::getMenuParams();

        $arrayRawGamesOwned = BGGData::getGamesOwned();

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

            if($arrayGame['playingtime'] >= 60) {
                $classes[] = 'longgame';
            } elseif($arrayGame['playingtime'] <= 30) {
                $classes[] = 'shortgame';
            }

            $arrayGame['class'] = implode(' ', $classes);

            $arrayGame['tooltip'] = 'Nb partie joué : ' . $arrayGame['numplays'];
            $arrayGame['tooltip'] .= '<br>Durée d\'une partie : ' . $arrayGame['playingtime'] . ' minutes';

            $arrayGames[$idGame] = $arrayGame;
        }

        $params['games'] = $arrayGames;

        $params = array_merge($params, $paramsMenu);

        return \View::make('collection', $params);
    }
}
