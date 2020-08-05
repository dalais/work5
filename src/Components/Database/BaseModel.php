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
     * @var \PDO $db
     */
    protected $db;

    function __construct()
    {
        $this->db = Db::getInstance()->conn();
    }

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

        if (empty($props)) {
            // Создание новой записи


            //Извлекаем названия полей таблицы
            $stmt = self::dbConn()->prepare("DESCRIBE " . self::table());
            $stmt->execute();
            $props = array_flip($stmt->fetchAll(\PDO::FETCH_COLUMN));

            // Формируем данные и поля для запроса
            $data = [];
            $columns = [];
            $bindes = [];
            foreach (array_keys($props) as $value) {
                if (isset($this->{$value})) {
                    $bindes[] = ':' . $value;
                    $columns[] = $value;
                    $data[$value] = $this->{$value};
                }
            }

            // Сохраняем запись
            $sql = "INSERT INTO "
                . self::table() . "("
                . implode(',', $columns)
                . ") VALUES (" . implode(',', $bindes) . ")";
            $stmt = self::dbConn()->prepare($sql);
            $stmt->execute($data);
            if ($stmt->rowCount()) {
                return true;
            } else {
                return false;
            }


        } else {
            // Изменение существующей записи


            if (empty($this->changed_properties)) {
                return null;
            }
            foreach ($props as $key => $value) {
                foreach ($this as $k => $v) {
                    if ($key === $k && $value !== $v && $k != 'id') {
                        $this->changed_properties[$k] = $v;
                    }
                }
            }
            $data = $this->changed_properties;
            $bindedColumns = [];
            foreach (array_keys($data) as $value) {
                $bindedColumns[] = $value . '=:' . $value;
            }
            $data['id'] = $props['id'];
            $sql = "UPDATE " . self::table() . " SET " . implode(',', $bindedColumns) . " WHERE id=:id";
            $stmt = self::dbConn()->prepare($sql);
            $stmt->execute($data);

            if ($stmt->rowCount()) {
                return true;
            } else {
                return false;
            }
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
     * Создание записи новой модели
     *
     * @param array $data
     */
    public static function create(array $data)
    {
        // Извлекаем поля таблицы
        $stmt = self::dbConn()->prepare("DESCRIBE " . self::table());
        $stmt->execute();

        // Сравниваем столбцы существующие в таблице
        // с полями полученных на вход данных и исключаем не совпавшие поля
        $columns = array_values($stmt->fetchAll(\PDO::FETCH_COLUMN));
        $dataKeys = array_keys($data);
        $intersect = array_intersect($columns, $dataKeys);

        // Формируем строки и данные для запроса
        $handledData = [];
        $bindes = [];
        $needleColumns = [];
        foreach ($columns as $value) {
            if (in_array($value, $intersect)) {
                $bindes[] = ':' . $value;
                $needleColumns[] = $value;
                $handledData[$value] = $data[$value];
            }
        }

        $sql = "INSERT INTO "
            . self::table() . "("
            . implode(',', $needleColumns)
            . ") VALUES (" . implode(',', $bindes) . ")";
        $stmt = self::dbConn()->prepare($sql);
        $stmt->execute($data);

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
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

        // Если запрошена одна запись
        if (is_int($ids) || is_array($ids) && count($ids) === 1) {
            $stmt = self::dbConn()->prepare("SELECT * FROM " . self::table() . " WHERE id=:id");
            $stmt->bindParam(':id', $ids);
            $stmt->execute();
            $single = $stmt->fetch();
        }

        // Если запрошено несколько записей
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