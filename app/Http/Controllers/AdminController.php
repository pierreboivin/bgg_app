<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class AdminController extends Controller
{
    public function home()
    {
        return \View::make('admin.home');
    }

}
