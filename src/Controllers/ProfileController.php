<?php

namespace App\Controllers;

use App\Components\Auth;
use App\Components\Database\Db;
use App\Components\Interfaces\RequestInterface as Request;
use App\Controllers\Base\Controller;

class ProfileController extends Controller
{

    public function index()
    {
        include __DIR__ . '/../views/profile/index.php';
    }

    public function edit()
    {
        $user = Auth::user();
        include_once __DIR__ . '/../views/profile/edit.php';
    }

    public function update(Request $request)
    {
        $this->postValidation($request->getBody(),[
            'firstname' => ['string'],
            'lastname' => ['string'],
            'middlename' => ['string'],
        ]);
        if (empty($this->errors)) {
            /** @var \PDO $db */
            $db = Db::getInstance()->conn();
            $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, middlename=:middlename WHERE id=:id";
            $stmt = $db->prepare($sql);
            $userId = Auth::id();
            $stmt->bindParam(':firstname', $request->getBody()['firstname'], \PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $request->getBody()['lastname'], \PDO::PARAM_STR);
            $stmt->bindParam(':middlename', $request->getBody()['middlename'], \PDO::PARAM_STR);
            $stmt->bindParam(':id', $userId, \PDO::PARAM_INT);
            $stmt->execute();
            flash('success', 'Данные профиля успешно изменены');
        } else {
            foreach ($this->errors as $key => $error) {
                flash($key, $error);
            }
        }
        header("Location: /profile/edit");
    }

    public function passwordChange(Request $request)
    {
        $this->postValidation($request->getBody(),[
            'password' => ['required','string','password','confirm'],
            'confirm' => ['required','string']
        ]);
        if (empty($this->errors)) {
            /** @var \PDO $db */
            $db = Db::getInstance()->conn();
            $sql = "UPDATE users SET password=:password WHERE id=:id";
            $stmt = $db->prepare($sql);
            $userId = Auth::id();
            $hashedPassword = password_hash($request->getBody()['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword, \PDO::PARAM_STR);
            $stmt->bindParam(':id', $userId, \PDO::PARAM_INT);
            $stmt->execute();
            flash('success', 'Пароль успешно изменен');
        } else {
            foreach ($this->errors as $key => $error) {
                flash($key, $error);
            }
        }
        header("Location: /profile/edit");
    }
}