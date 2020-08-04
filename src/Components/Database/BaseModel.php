<?php

namespace App\Components\Database;

use App\Components\Interfaces\ModelInterface;

class BaseModel implements ModelInterface
{
    /**
     * @var array $original_properties
     */
    private $original_properties = [];

    /**
     * @var array $changed_properties
     */
    private $changed_properties = [];

    private $meta = [];

    /**
     * @return \PDO
     */
    private static function dbConn()
    {
        return Db::getInstance()->conn();
    }

    /**
     * Получаем название таблицы
     *
     * @return string
     */
    private static function table()
    {
        return strtolower(preg_replace('/^(\w+\\\)*/', '', static::class)) . "s";
    }

    /**
     * Перевод типов столбцов, полученных посредством PDO
     * в типы понятные для php
     *
     * @param $orig
     * @return mixed
     */
    private static function translateNativeType($orig)
    {
        $trans = array(
            'VAR_STRING' => 'string',
            'STRING' => 'string',
            'BLOB' => 'blob',
            'LONGLONG' => 'int',
            'LONG' => 'int',
            'SHORT' => 'int',
            'DATETIME' => 'datetime',
            'DATE' => 'date',
            'DOUBLE' => 'real',
            'TIMESTAMP' => 'timestamp'
        );
        return $trans[$orig];
    }

    /**
     * Сохраняем измененные свойства объекта модели
     *
     * @return bool|null
     */
    public function save()
    {
        $props = $this->original_properties;
        foreach ($props as $key => $value) {
            foreach ($this as $k => $v) {
                if ($key === $k && $value !== $v && $k != 'id') {
                    $this->changed_properties[$k] = $v;
                }
            }
        }
        if (empty($this->changed_properties)) {
            return null;
        }
        $data = $this->changed_properties;
        $columns = [];
        foreach (array_keys($data) as $value) {
            $columns[] = $value . '=:' . $value;
        }
        $columnsStr = implode(',', $columns);
        $data['id'] = $props['id'];
        $sql = "UPDATE " . self::table() . " SET " . $columnsStr . " WHERE id=:id";
        $stmt = self::dbConn()->prepare($sql);
        $stmt->execute($data);
        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Обновление записей
     *
     */
    public function update()
    {
        //TODO
    }

    /**
     * Получить все записи
     *
     * @return array
     **/
    public static function getAll()
    {
        $stmt = self::dbConn()->prepare("SELECT * FROM " . self::table());
        $stmt->execute();
        $multi = $stmt->fetchAll();

        // Получаем метаданные столбцов
        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            $mt = $stmt->getColumnMeta($i);
            $meta[$mt['name']] = $mt;
        }

        $result = [];
        $class = static::class;
        foreach ($multi as $k => $row) {
            $model = new $class;
            foreach ($row as $key => $value) {
                $model->$key = $value;
            }
            $model->meta = $meta;
            $result[$k] = $model;
        }

        return $result;
    }


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
        $meta = [];
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

        // Получаем метаданные столбцов
        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            $mt = $stmt->getColumnMeta($i);
            $meta[$mt['name']] = $mt;
        }

        $result = [];
        $class = static::class;
        if (!empty($single)) {
            $model = new $class;
            foreach ($single as $key => $value) {
                $model->original_properties[$key] = $value;
                $model->{$key} = $value;
            }
            $model->meta = $meta;
            $result = $model;
        }
        if (!empty($multi)) {
            foreach ($multi as $k => $row) {
                $model = new $class;
                foreach ($row as $key => $value) {
                    $model->$key = $value;
                }
                $model->meta = $meta;
                $result[$k] = $model;
            }

        }

        if (empty($result)) {
            return null;
        }
        return $result;
    }

    /**
     * Удаление записи из базы данных
     *
     * @return bool
     */
    public function delete()
    {
        $props = $this->original_properties;
        $stmt = self::dbConn()->prepare("DELETE FROM " . self::table() . " WHERE id=:id");
        $stmt->bindParam(':id', $props['id']);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }
}