<?php

namespace Orm\Repository;

use Collection\Collection;
use Orm\DataBase\DataBase;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\DataBase\DataBaseFactory;
use Orm\Model\Model;
use Orm\OrmException;
use Orm\Peer\Peer;
use Orm\Query\Query;
use Orm\QueryBuilder\QueryBuilder;
use Orm\QueryBuilder\QueryBuilderPeers;

abstract class Repository extends Peer
{
    /**
     * @return Collection
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function find(): Collection
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->select('*')
            ->from($this->getTableName());

        $query = new Query($queryBuilder, $this->getDataBase());
        $query->execute();

        return new Collection($query->getResults());
    }

    /**
     * @param int $primaryKey
     * @return Model
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function findOne(int $primaryKey): Model
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->select('*')
            ->from($this->getTableName())
            ->where($this->getPrimaryKey(), QueryBuilderPeers::IS, ':primaryKey', [
                'primaryKey' => $primaryKey
            ]);

        $query = new Query($queryBuilder, $this->getDataBase());
        $query->execute();
        $classname = $this->getModel();

        return new $classname($query->getResults());
    }

    /**
     * @return QueryBuilder
     */
    private function getQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder();
    }

    /**
     * @return DataBase
     */
    private function getDataBase(): DataBase
    {
        return DataBaseFactory::getInstance();
    }
}