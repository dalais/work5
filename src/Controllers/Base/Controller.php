<?php

namespace App\Controllers\Base;

use App\Components\Database\Db;

class Controller
{
    public $errors = [];

    public function postValidation($post, array $allRules)
    {
        foreach ($allRules as $field => $rules) {
            foreach ($rules as $rule) {
                if ($rule == 'required') {
                    if (!isset($post[$field]) || $post[$field] === '') {
                        $this->errors[$field] = "Поле " . $field . " обязательно для заполнения";
                        break;
                    }
                }
                if ($rule == 'string') {
                    if (isset($post[$field]) && !is_string($post[$field])) {
                        $this->errors[$field] = "Поле " . $field . " поле должно быть строкой";
                        break;
                    }
                    if (strlen($post[$field]) > 50) {
                        $this->errors[$field] = 'Слишком длинная строка';
                        break;
                    }
                }
                if ($rule == 'email') {
                    if (isset($post[$field]) && !filter_var($post[$field], FILTER_VALIDATE_EMAIL)) {
                        $this->errors[$field] = 'Некорректный email';
                        break;
                    }
                }
                if ($rule == 'password' && strlen($post[$field]) < 6) {
                    $this->errors[$field] = 'Пароль должен содержать не менее 6 символов';
                    break;
                }
                if ($rule == 'confirm') {
                    if (!isset($post['confirm'])) {
                        $this->errors[$field] = 'Требуется поле подтвреждения пароля';
                        break;
                    }
                    if (isset($post['password']) && $post['password'] !== '' && isset($post['confirm']) && $post['confirm'] !== '') {
                        if ($post['password'] !== $post['confirm']) {
                            $this->errors[$field] = 'Пароли не совпадают';
                            break;
                        }
                    }
                }
                if ($rule == 'unique' && $post[$field] == 'email') {
                    if (isset($post[$field]) && $post[$field] !== '') {
                        $sql = "SELECT email FROM users WHERE email=?";
                        /** @var \PDO $db */
                        $db = Db::getInstance()->conn();
                        $stmt = $db->prepare($sql);
                        $stmt->execute([$post['email']]);
                        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
                        if ($res !== null) {
                            $this->errors[$field] = 'Этот email уже используется.';
                            break;
                        }
                    }
                }
            }
        }
    }
}