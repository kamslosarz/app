<?php

namespace Orm\DataBase;

use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\DataBase\DatabaseAdapter\DatabaseAdapterInterface;

class DataBase
{
    protected DatabaseAdapterInterface $databaseAdapter;

    public function __construct(DatabaseAdapterInterface $databaseAdapter)
    {
        $this->databaseAdapter = $databaseAdapter;
    }

    /**
     * @param string $query
     * @param array $binds
     * @return array|null
     * @throws DataBaseAdapterException
     */
    public function query(string $query, array $binds): ?array
    {
        $this->databaseAdapter->query($query, $binds);

        return $this->databaseAdapter->getResults();
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        return $this->databaseAdapter->getLastInsertId();
    }
}