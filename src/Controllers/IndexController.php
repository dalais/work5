<?php

namespace App\Controllers;

use App\Models\User;

class IndexController
{
    public function index()
    {
        include __DIR__.'/../views/index.php';
    }

    public function greeting()
    {
        include __DIR__.'/../views/greeting.php';
    }

    public function notfound()
    {
        include __DIR__.'/../views/notfound.php';
    }
}