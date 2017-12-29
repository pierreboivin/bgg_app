<?php

namespace App\Lib;

class GamesList
{
    public static function processGameList($gamesCollection)
    {
        $params = [];
        $allMechanics = [];
        $arrayGames = [];

        foreach ($gamesCollection as $idGame => $gameProperties) {
            $classes = [];

            $arrayGame = self::preProcessGameInfo($gameProperties);

            if (is_array($gameProperties['name'])) {
                foreach ($gameProperties['name'] as $name) {
                    if ($name['@attributes']['type'] == 'primary') {
                        $arrayGame['name'] = $name['@attributes']['value'];
                        break;
                    }
                }
            }

            if ($arrayGame['playingtime'] <= 30) {
                $classes[] = '30minus';
            } elseif ($arrayGame['playingtime'] > 30 && $arrayGame['playingtime'] <= 60) {
                $classes[] = '31to60';
            } elseif ($arrayGame['playingtime'] > 61 && $arrayGame['playingtime'] <= 120) {
                $classes[] = '61to120';
            } elseif ($arrayGame['playingtime'] > 120) {
                $classes[] = '121plus';
            }

            if (isset($gameProperties['detail']['boardgamemechanic'])) {
                foreach ($gameProperties['detail']['boardgamemechanic'] as $mechanic) {
                    $classes[] = Utility::getKeyByString($mechanic['value']);
                    $allMechanics[] = $mechanic['value'];
                }
            }

            if ($arrayGame['minplayer'] && $arrayGame['maxplayer']) {
                $begin = (int)$arrayGame['minplayer'];
                $end = (int)$arrayGame['maxplayer'];

                if ($begin == 1) {
                    $classes[] = 'players1';
                }
                if ($end >= 7) {
                    $classes[] = 'playersplus';
                }
                for ($i = $begin; $i <= $end; $i++) {
                    $classes[] = 'players' . $i;

                    if (isset($gameProperties['poll']['process_suggested_numplayers']['best']) && $gameProperties['poll']['process_suggested_numplayers']['best'] == $i) {
                        $classes[] = 'players' . $i . 'best';
                    }
                    if (isset($gameProperties['poll']['process_suggested_numplayers']['recommended']) && $gameProperties['poll']['process_suggested_numplayers']['recommended'] == $i) {
                        $classes[] = 'players' . $i . 'recommended';
                    }
                }
            }

            $arrayGame['class'] = implode(' ', $classes);

            $infoTooltipLines = [];
            if (isset($arrayGame['numplays'])) {
                $infoTooltipLines[] = 'Nombre de parties joués : ' . $arrayGame['numplays'];
            }
            if ($arrayGame['minplayer'] > 0 && $arrayGame['maxplayer'] > 0) {
                $infoTooltipLines[] = 'Nombre de joueurs : ' . $arrayGame['minplayer'] . ' à ' . $arrayGame['maxplayer'];
            }
            $infoTooltipLines[] = 'Durée d\'une partie : ' . $arrayGame['playingtime'] . ' minutes';
            $infoTooltipLines[] = 'Complexité : ' . round($gameProperties['weight'], 2) . ' / 5';
            $infoTooltipLines[] = 'Évaluation : ' . $arrayGame['rating'] . ' / 10';
            if (isset($gameProperties['privateinfo']['@attributes']['acquisitiondate'])) {
                $infoTooltipLines[] = 'Date d\'acquisition : ' . $gameProperties['privateinfo']['@attributes']['acquisitiondate'];
            }
            $arrayGame['tooltip'] = implode('<br>', $infoTooltipLines);

            $arrayGames[$idGame] = $arrayGame;
        }

        $params['games'] = $arrayGames;

        $mechanics = array_values(array_unique($allMechanics));
        foreach ($mechanics as $mechanic) {
            $params['mechanics'][Utility::getKeyByString($mechanic)] = $mechanic;
        }
        ksort($params['mechanics']);

        return $params;
    }

    /**
     * @param $gameProperties
     * @param $arrayGame
     * @return array
     */
    public static function preProcessGameInfo($gameProperties)
    {
        $arrayGame = $gameProperties;
        $arrayGame['image'] = $gameProperties['thumbnail'];
        $arrayGame['playingtime'] = isset($gameProperties['playingtime']) ? $gameProperties['playingtime'] : 0;
        $arrayGame['minplayer'] = isset($gameProperties['minplayer']) ? $gameProperties['minplayer'] : 0;
        $arrayGame['maxplayer'] = isset($gameProperties['maxplayer']) ? $gameProperties['maxplayer'] : 0;

        if (isset($gameProperties['expansions'])) {
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


        if (!isset($gameProperties['rating']) && isset($gameProperties['ratings'])) {
            $arrayGame['rating'] = $gameProperties['ratings']['average'];
        }
        return $arrayGame;
    }
}