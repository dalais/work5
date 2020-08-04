<?php

namespace App\Controllers;

use App\Models\User;

class IndexController
{
    public function index()
    {
        $user = User::findByID(9);
        var_dump($user->delete());
    }
}