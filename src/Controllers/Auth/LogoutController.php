<?php

namespace App\Controllers\Auth;

class LogoutController extends AuthController
{
    public function index()
    {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', 0, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
        header("Location: /");
    }
}