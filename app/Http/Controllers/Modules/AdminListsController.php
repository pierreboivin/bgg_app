<?php

namespace App\Http\Controllers\Modules;

use App\Lists;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminListsController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $lists = \App\Lists::findAllOnlyAccess();
        return \View::make('modules.lists.admin.index', compact('lists'));
    }

    public function create()
    {
        return \View::make('modules.lists.admin.edit');
    }

    public function store()
    {
        $input = Input::all();
        $validation = Validator::make($input, Lists::$rules);

        if ($validation->passes()) {
            $input['user_id'] = Auth::user()->id;
            Lists::create($input);
            return Redirect::route('modules.lists.admin.index');
        }

        return Redirect::route('modules.lists.admin.create')
            ->withInput()
            ->withErrors($validation);
    }

    public function edit($id)
    {
        $list = Lists::findOnlyAccess($id);
        if (is_null($list)) {
            Session::flash('error', 'Liste introuvable');
            return Redirect::route('modules.lists.admin.index');
        }
        return \View::make('modules.lists.admin.edit', compact('list'));
    }

    public function update($id)
    {
        $input = Input::all();
        $validation = Validator::make($input, Lists::$rules);
        if ($validation->passes()) {
            $list = Lists::find($id);
            $list->update($input);
            return Redirect::route('modules.lists.admin.index', $id);
        }
        return Redirect::route('modules.lists.admin.edit', $id)
            ->withInput()
            ->withErrors($validation);
    }

    public function hasAccess($list)
    {
        if (Auth::user()->type == 'admin') {
            return true;
        } else if ($list->user_id == Auth::user()->id) {
            return true;
        }
        return false;
    }

    public function destroy($id)
    {
        Lists::find($id)->delete();
        return Redirect::route('modules.lists.admin.index');
    }

}
