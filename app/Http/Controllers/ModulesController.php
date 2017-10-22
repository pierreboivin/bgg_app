<?php

namespace App\Http\Controllers;

class ModulesController extends Controller
{
    public function home()
    {
        return \View::make('modules.home');
    }

}
