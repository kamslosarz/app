<?php

namespace Orm\DataBase;

use Orm\DataBase\DatabaseAdapter\PdoAdapter;

abstract class DataBaseFactory
{
    /**
     * @return DataBase
     * @throws DatabaseAdapter\DataBaseAdapterException
     */
    public static function getInstance(): DataBase
    {
        return new DataBase(new PdoAdapter([
            'dsn' => sprintf('sqlite:%s', APP_DIR.'/data/database.sql'),
            'username' => null,
            'password' => null,
            'options' => null,
        ]));
    }
}