<?php

namespace App\Controllers\Auth;

use App\Components\Interfaces\RequestInterface as Request;
use App\Models\User;

class RegisterController extends AuthController
{
    public function index(Request $request)
    {
        $_SESSION = [];
        $this->postValidation($request->getBody(),[
            'email' => ['required','email','unique'],
            'password' => ['required','string','password','confirm'],
            'confirm' => ['required','string'],
            'firstname' => ['string'],
            'lastname' => ['string'],
            'middlename' => ['string'],
        ]);
        $errors = $this->errors;
        if (count($errors) == 0) {
            $newUser = new User();
            $newUser->create($request->getBody());
            include __DIR__.'/../../views/greeting.php';
        } else {
            foreach ($this->errors as $key => $error) {
                flash($key, $error);
            }
            header('Location: /register');
        }
    }
}