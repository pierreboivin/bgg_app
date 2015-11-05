<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Cache;

class ToolsController extends Controller
{
    public function flushCaches()
    {
        Cache::flush();
        dd("Cache cleared");
    }

    public function load()
    {

    }

}
