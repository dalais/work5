<?php

namespace App\Components;

use App\Components\Database\Db;

class Auth
{
    public static function id()
    {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
    }

    public static function user()
    {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $db = Db::getInstance()->conn();
        $sql = "SELECT * FROM users WHERE id=?";
        /** @var \PDO $db */
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $res;
    }
}