<?php

namespace App;

use App\Components\Http\Request;
use App\Components\Router;

class App
{
    public function run()
    {
        $router = new Router(new Request);
        foreach (glob(__DIR__."/routes/*.php") as $filename) {
            include_once $filename;
        }
    }
}