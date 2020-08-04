<?php

namespace App\Controllers;

use App\Controllers\Base\Controller;

class UserController extends Controller
{
    public function login()
    {
        include __DIR__ . '/../views/user/login.php';
    }

    public function register()
    {
        include __DIR__ . '/../views/user/register.php';
    }
}