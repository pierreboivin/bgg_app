<?php namespace App\Http\Controllers\Modules;

use App\Http\Requests;
use App\Lists;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ListsController extends \App\Http\Controllers\Controller
{

    public function index()
    {
        $lists = \App\Lists::all();
        return \View::make('modules.lists.index', compact('lists'));
    }

    public function create()
    {
        return \View::make('modules.lists.edit');
    }

    public function store()
    {
        $input = Input::all();
        $validation = Validator::make($input, Lists::$rules);

        if ($validation->passes()) {
            Lists::create($input);
            return Redirect::route('modules.lists.index');
        }

        return Redirect::route('modules.lists.create')
            ->withInput()
            ->withErrors($validation);
    }

    public function edit($id)
    {
        $user = Lists::find($id);
        if (is_null($user)) {
            return Redirect::route('modules.lists.index');
        }
        return \View::make('modules.lists.edit', compact('user'));
    }

    public function update($id)
    {
        $input = Input::all();
        $validation = Validator::make($input, Lists::$rules);
        if ($validation->passes())
        {
            $user = Lists::find($id);
            $user->update($input);
            return Redirect::route('modules.lists.index', $id);
        }
        return Redirect::route('modules.lists.edit', $id)
            ->withInput()
            ->withErrors($validation);
    }

    public function destroy($id)
    {
        Lists::find($id)->delete();
        return Redirect::route('modules.lists.index');
    }

}
