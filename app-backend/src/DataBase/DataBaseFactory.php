<?php

namespace App\DataBase;

use App\Logger\LoggerFactory;
use Orm\DataBase\DataBase;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\DataBase\DatabaseAdapter\PdoAdapter;

abstract class DataBaseFactory
{
    /**
     * @return DataBase
     * @throws DataBaseAdapterException
     */
    public static function getInstance(): DataBase
    {
        return new DataBase(new PdoAdapter([
            'dsn' => sprintf('sqlite:%s', APP_DIR . '/data/database.sql'),
            'username' => null,
            'password' => null,
            'options' => null,
        ]),
            LoggerFactory::getInstance()
        );
    }
}