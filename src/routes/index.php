<?php
/** @var $router \App\Components\Router */

$router->get('/', "App\Controllers\IndexController@index");
$router->get('/notfound', "App\Controllers\IndexController@notfound");
$router->get('/login', "App\Controllers\UserController@login");
$router->get('/register', "App\Controllers\UserController@register");
$router->get('/greeting', "App\Controllers\IndexController@greeting");
$router->post('/auth/register', "App\Controllers\Auth\RegisterController@index");
$router->post('/auth/login', "App\Controllers\Auth\LoginController@index");
if (isset($_SESSION['auth']) && $_SESSION['auth']) {
    $router->get('/profile', "App\Controllers\ProfileController@index");
    $router->get('/profile/edit', "App\Controllers\ProfileController@edit");
    $router->post('/profile/update', "App\Controllers\ProfileController@update");
    $router->post('/profile/psw_change', "App\Controllers\ProfileController@passwordChange");
    $router->get('/auth/logout', "App\Controllers\Auth\LogoutController@index");
}

