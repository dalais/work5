<?php

namespace App\Controllers\Auth;

use App\Components\Database\Db;
use App\Controllers\Base\Controller;

class AuthController extends Controller
{

    /**
     * Валидация пользователя
     *
     * @param array $post
     * @return array
     */
    protected function loginCheck(array $post)
    {
        $resArray = [];
        if (empty($this->errors)) {
            $sql = "SELECT * FROM users WHERE email=?";
            /** @var \PDO $db */
            $db = Db::getInstance()->conn();
            $stmt = $db->prepare($sql);
            $stmt->execute([$post['email']]);
            try {
                $res = $stmt->fetch(\PDO::FETCH_ASSOC);
                $verify = password_verify($post['password'], $res['password']);
                if (!$res || !$verify) {
                    $this->errors['error'] = 'Некорректный email или пароль';
                }
                if ($res && $verify) {
                    $resArray['auth'] = true;
                    $resArray['user_id'] = (int)$res['id'];
                }
            } catch (\PDOException $e) {
                $this->errors['error'] = $e->getMessage();
            }
        }
        return $resArray;
    }
}