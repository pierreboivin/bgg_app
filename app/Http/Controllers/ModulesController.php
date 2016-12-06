<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class ModulesController extends Controller
{
    public function home()
    {
        return \View::make('modules.home');
    }

}
