<?php

namespace App\Components\Database;

use App\Components\Interfaces\ModelInterface;

class BaseModel implements ModelInterface
{
    private $id;


    /**
     * @return \PDO
     */
    private static function dbConn()
    {
        return Db::getInstance()->conn();
    }

    private static function table()
    {
        return strtolower(preg_replace('/^(\w+\\\)*/', '', static::class)) . "s";
    }

    /*public function save()
    {
        $props = $this->changed_properties;
        unset($this->changed_properties);
        $user = [];
        foreach ($this as $key => $value) {
            $user[$key] = $value;
        }
        if (!empty($props)) {
            foreach ($props as $item) {
                foreach ($item as $key => $value) {
                    if ($value === '' || $key === 'id') {
                        continue;
                    }
                    $user[$key] = $value;
                }
            }
        }
        return $this->findByID($this->id);
    }*/

    /**
     * Получить все записи
     *
     * @return array
     **/
    /*public static function getAll()
    {
        $users_ids = array_column(self::db_file(), 'id');
        $users = [];
        foreach ($users_ids as $id) {
            $users[] = self::findByID($id);
        }
        return $users;
    }*/

    /**
     * Выбрать запись по id
     *
     * @param $ids mixed
     * @return mixed
     **/
    public static function findByID($ids)
    {
        $single = [];
        $multi = [];
        if (is_int($ids) || is_array($ids) && count($ids) === 1) {
            $stmt = self::dbConn()->prepare("SELECT * FROM " . self::table() . " WHERE id=:id");
            $stmt->bindParam(':id', $ids);
            $stmt->execute();
            $single = $stmt->fetch();
        }

        if (is_array($ids) && count($ids) > 1) {
            $idsArr = implode(',', $ids);
            $stmt = self::dbConn()->prepare("SELECT * FROM " . self::table() . " WHERE id IN (" . $idsArr . ")");
            $stmt->execute();
            $multi = $stmt->fetchAll();
        }
        $result = [];
        $class = static::class;
        if (!empty($single)) {
            $model = new $class;
            foreach ($single as $key => $value) {
                $model->{$key} = $value;
            }
            $result[] = $model;
        }
        if (!empty($multi)) {
            foreach ($multi as $k => $row) {
                $model = new $class;
                foreach ($row as $key => $value) {
                    $model->{$key} = $value;
                }
                $result[$k] = $model;
            }

        }

        if (empty($result)) {
            return null;
        }
        return $result;
    }

    /**
     * Удаление запись по id
     *
     * @return void
     **/
    /*public static function delete($id)
    {
        $user = self::findByID($id);
        if ($user !== null) {
            $users = self::db_file();
            $k = null;
            foreach ($users as $key => $value) {
                if ($value['id'] === $user->id) {
                    $k = $key;
                }
            }
            unset($users[$k]);
            file_put_contents("users.php", '<?php'.PHP_EOL.'return ' . var_export($users, true) . ';');
            echo "Пользователь успешно удален";
        } else {
            echo "Не существует такого пользователя.";
        }
    }*/
}