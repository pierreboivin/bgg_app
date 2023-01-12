<?php

namespace App\Http\Controllers;

use App\Lib\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

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

        $validator = Validator::make(Request::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::to('/login')
                ->withErrors($validator)
                ->withInput(Request::except('password'));
        } else {
            $userdata = array(
                'email' => Request::get('email'),
                'password' => Request::get('password')
            );

            if (Auth::attempt($userdata, true)) {
                Session::forget('username');
                return Redirect::to('/home/' . Auth::user()->bggusername);
            } else {
                $validatorCustom[] = 'Erreur de connexion';
                return Redirect::to('/login')->withErrors(array('register' => $validatorCustom));
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
        $validator = Validator::make(Request::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::to('/')
                ->withErrors($validator)
                ->withInput();
        } else {
            Auth::logout();
            return Redirect::to('/home/' . Request::get('username'));
        }
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('/')->withSuccess('Vous êtes déconnecté');
    }


}
