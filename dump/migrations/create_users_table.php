<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Components\Database\Db;

/** @var \PDO $dbconn */
$dbconn = Db::getInstance()->conn();

$sql = "
    CREATE TABLE users (
        id INTEGER(11) UNSIGNED AUTO_INCREMENT,
        login VARCHAR(50) NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        firstname VARCHAR(50) NULL,
        lastname VARCHAR(50) NULL,
        middlename VARCHAR(50) NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB;
";

try {
    $dbconn->exec($sql);
} catch (\PDOException $e) {
    echo $e->getMessage();
}