<?php

namespace App\Http\Controllers;

use App\Lib\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login()
    {
        $paramsMenu = Page::getMenuParams();
        return \View::make('login', $paramsMenu);
    }

    public function userLogin()
    {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required'
        );
        $messages = [
            'email.required' => 'Le champs Adresse courriel est obligatoire.',
            'email.email' => 'Le champs Adresse courriel n\'a pas le bon format.',
            'password.required' => 'Le champs Mot de passe est obligatoire.'
        ];

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::to('/')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $userdata = array(
                'email' => Input::get('email'),
                'password' => Input::get('password')
            );

            if (Auth::attempt($userdata, true)) {
                Session::forget('username');
                return Redirect::to('/home/' . Auth::user()->bggusername);
            } else {
                $validatorCustom[] = 'Erreur de connexion';
                return Redirect::to('/')->withErrors(array('register' => $validatorCustom));
            }

        }
    }

    public function guestLogin()
    {
        $rules = array(
            'username' => 'required',
        );
        $messages = [
            'username.required' => 'Le champs Nom d\'utilisateur est obligatoire.',
        ];
        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::to('/')
                ->withErrors($validator)
                ->withInput();
        } else {
            Auth::logout();
            return Redirect::to('/home/' . Input::get('username'));
        }
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('/')->withSuccess('Vous êtes déconnecté');
    }


}
