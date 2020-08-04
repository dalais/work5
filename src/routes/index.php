<?php
/** @var $router \App\Components\Router */

$router->get('/', "App\Controllers\IndexController@index");
$router->post('/auth/register', "App\Controllers\Auth\RegisterController@index");
$router->post('/auth/login', "App\Controllers\Auth\LoginController@index");