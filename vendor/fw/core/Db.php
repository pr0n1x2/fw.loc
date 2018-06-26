<?php

namespace fw\core;

use R;

class Db
{
    use TSingletone;

    protected $pdo;
    public static $countSql = 0;
    public static $queries = [];

    protected function __construct()
    {
        $db = require ROOT . '/config/config_db.php';
        require LIBS . '/rb.php';

        R::setup($db['dsn'], $db['user'], $db['pass']);
        R::freeze(true);
        //R::debug( TRUE );
        //R::funcyDebug(true);
    }
}
