<?php

namespace App\Components\Database;

use App\Components\Pattern\Singleton;

class Db extends Singleton
{
    /** @var $db \PDO */
    private $db;

    protected function __construct()
    {
        $this->getConnection();
    }

    private function getConnection()
    {
        if (!file_exists(__DIR__ . "/../../configs/db.php")) {
            exit('Database connect error. The settings file is not found');
        }
        $dbParams = include_once __DIR__ . "/../../configs/db.php";
        try {
            $dbh = new \PDO($dbParams['driver']
                .":host=" . $dbParams['host']
                .";dbname=" . $dbParams['database'],
                $dbParams['user'],
                $dbParams['password']
            );
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->db = $dbh;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return \PDO
     */
    public function conn()
    {
        return $this->db;
    }
}