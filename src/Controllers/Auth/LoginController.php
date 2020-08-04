<?php

namespace App\Controllers\Auth;

use App\Components\Interfaces\RequestInterface;

class LoginController extends AuthController
{
    public function index(RequestInterface $request)
    {
        $_SESSION = [];
        $authData = [];
        // Проверка post данных
        $this->postValidation($request->getBody(),[
            'email' => ['required','string','email'],
            'password' => ['required','string']
        ]);
        // Проверка наличия пользователя
        if (empty($this->errors)) {
            $authData = $this->loginCheck($request->getBody());
        }
        if (empty($this->errors)) {
            // При успешной проверке сохраняем соответствующие данные в сессию
            $_SESSION = $authData;
            header("Location: /profile");
        } else {
            // Сохранение ошибок в сессии для flash-сообщений
            foreach ($this->errors as $key => $error) {
                flash($key, $error);
            }
            header("Location: /login");
        }
    }
}