<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lib\PersistentCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class ToolsController extends Controller
{
    public function flushCaches()
    {
        Cache::flush();
        Session::flash('success', 'Les caches ont été effacés avec succès.');
        return redirect('admin');
    }
    public function flushPersistentCaches()
    {
        PersistentCache::flush();
        Session::flash('success', 'Les caches persistentes ont été effacés avec succès.');
        return redirect('admin');
    }

}
