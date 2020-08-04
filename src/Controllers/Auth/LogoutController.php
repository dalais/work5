<?php

namespace App\Controllers\Auth;

class LogoutController extends AuthController
{
    public function index()
    {
        $_SESSION = [];
        header("Location: /");
    }
}