<?php

namespace App\Http\Controllers\Modules;

use App\Lib\BGGData;
use App\Lib\GamesList;
use App\Lib\Stats;
use App\Lists;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use View;

class ViewListsController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $lists = Lists::all();
        return View::make('modules.lists.view.index', compact('lists'));
    }

    public function show($slug)
    {
        $params = [];
        $list = Lists::findBySlug($slug);

        $arrayGamesId = explode(chr(10), $list->data);

        if (count($arrayGamesId) <= 2 || count($arrayGamesId) > 1000) {
            Session::flash('error', 'La liste doit contenir entre 2 et 1000 jeux valides.');
            return redirect('/lists');
        }

        $arrayRawGames = BGGData::getListOfGames($arrayGamesId);
        $arrayGamesDetails = BGGData::getDetailOwned($arrayRawGames);
        foreach ($arrayGamesDetails as $key => $game) {
            $arrayGamesDetails[$key] = Stats::convertBggDetailInfo($arrayGamesDetails[$key]);
            $arrayGamesDetails[$key] = array_merge($arrayGamesDetails[$key], Stats::getDetailInfoGame($arrayGamesDetails[$key]));
        }

        $params['list'] = $list;
        $params = array_merge($params, GamesList::processGameList($arrayGamesDetails));

        return \View::make('modules.lists.view.show', $params);
    }
}
