<?php

namespace Orm\Query;

use Orm\DataBase\DataBase;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\OrmException;
use Orm\QueryBuilder\QueryBuilder;

class Query
{
    protected QueryBuilder $queryBuilder;
    protected DataBase $dataBase;
    protected ?array $results;

    /**
     * Query constructor.
     * @param QueryBuilder $queryBuilder
     * @param DataBase $dataBase
     */
    public function __construct(QueryBuilder $queryBuilder, DataBase $dataBase)
    {
        $this->queryBuilder = $queryBuilder;
        $this->dataBase = $dataBase;
    }

    /**
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function execute(): void
    {
        $this->results = $this->dataBase->query($this->queryBuilder->build(), $this->queryBuilder->getBinds());
    }

    /**
     * @return array|null
     */
    public function getResults(): ?array
    {
        return $this->results;
    }

    /**
     * @return array|null
     * @throws OrmException
     */
    public function getFirstResult(): ?array
    {
        if(!isset($this->results[0]))
        {
            throw new OrmException('Query returns empty results');
        }

        return $this->results[0];
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        return $this->dataBase->getLastInsertId();
    }
}