<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        $users = \App\User::all();
        return \View::make('admin.users.index', compact('users'));
    }

    public function create()
    {
        return \View::make('admin.users.edit');
    }

    public function store()
    {
        $input = Request::all();
        $validation = Validator::make($input, User::$rules);

        if ($validation->passes()) {
            $input['password'] = Hash::make($input['password']);
            User::create($input);
            return Redirect::route('admin.users.index');
        }

        return Redirect::route('admin.users.create')
            ->withInput()
            ->withErrors($validation);
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return Redirect::route('admin.users.index');
        }
        return \View::make('admin.users.edit', compact('user'));
    }

    public function update($id)
    {
        $input = Request::all();
        $validation = Validator::make($input, User::$rules);
        if ($validation->passes()) {
            $user = User::find($id);
            if ($input['password']) {
                $input['password'] = Hash::make($input['password']);
            } else {
                unset($input['password']);
                unset($input['password_confirmation']);
            }
            $user->update($input);
            return Redirect::route('admin.users.index', $id);
        }
        return Redirect::route('admin.users.edit', $id)
            ->withInput()
            ->withErrors($validation);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return Redirect::route('admin.users.index');
    }

}
