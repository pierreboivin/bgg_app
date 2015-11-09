<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class ToolsController extends Controller
{
    public function flushCaches()
    {
        Cache::flush();
        Session::flash('success', 'Les caches ont été effacés avec succès.');
        return redirect('home');
    }

}
