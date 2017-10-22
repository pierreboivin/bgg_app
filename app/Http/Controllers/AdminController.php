<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function home()
    {
        return \View::make('admin.home');
    }

}
