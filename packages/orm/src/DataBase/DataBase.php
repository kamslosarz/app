<?php

namespace Orm\DataBase;

use Logger\Logger;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\DataBase\DatabaseAdapter\DatabaseAdapterInterface;

class DataBase
{
    protected DatabaseAdapterInterface $databaseAdapter;
    protected Logger $logger;

    public function __construct(DatabaseAdapterInterface $databaseAdapter, Logger $logger)
    {
        $this->databaseAdapter = $databaseAdapter;
        $this->logger = $logger;
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
        $results = $this->databaseAdapter->getResults();
        $this->logger->log(sprintf('q:[%s] r[%s]', $query, sizeof($results)));

        return $results;
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        return $this->databaseAdapter->getLastInsertId();
    }
}