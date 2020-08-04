<?php

namespace App\Models;

use App\Components\Database\BaseModel;

/**
 * Class User
 *
 * @package App\Models
 */
class User extends BaseModel
{

    /**
     * Создание нового пользователя
     *
     * @param array $fields
     */
    public function create($fields)
    {
        $hashedPassword = password_hash($fields['password'], PASSWORD_DEFAULT);
        $firstname = isset($fields['firstname']) ? $fields['firstname'] : null;
        $lastname = isset($fields['lastname']) ? $fields['lastname'] : null;
        $middlename = isset($fields['middlename']) ? $fields['middlename'] : null;

        $sql = "INSERT INTO users (email, password, firstname, lastname, middlename) 
                    VALUES (:email, :password, :firstname, :lastname, :middlename)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':email', $fields['email'], \PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, \PDO::PARAM_STR);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':middlename', $middlename);

        try {
            $stmt->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}
