<?php

namespace App;

use App\Components\Http\Request;
use App\Components\Router;

class App
{
    public function run()
    {
        ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/storage/sessions'));
        ini_set('session.cookie_httponly',1);
        ini_set('session.use_only_cookies',1);
        session_start();
        $router = new Router(new Request);
        foreach (glob(__DIR__."/routes/*.php") as $filename) {
            include_once $filename;
        }
    }
}