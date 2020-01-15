<?php

namespace Orm\Repository;

use App\DataBase\DataBaseFactory;
use Collection\Collection;
use Orm\DataBase\DataBase;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\Model\Model;
use Orm\OrmException;
use Orm\Peer\Peer;
use Orm\Query\Query;
use Orm\QueryBuilder\QueryBuilder;
use Orm\QueryBuilder\QueryBuilderPeers;

abstract class Repository extends Peer
{
    /**
     * @param int $limit
     * @param int $offset
     * @return Collection
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function find(int $limit = null, int $offset = 0): Collection
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->select('*')
            ->from($this->getTableName())
            ->order($this->getPrimaryKey(), QueryBuilderPeers::ORDER_DESC);

        if(!is_null($limit))
        {
            $queryBuilder->limit($limit, $offset);
        }

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

        return new $classname($query->getFirstResult());
    }

    /**
     * @return int
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function count(): int
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->select(sprintf('Count(%s) as count', $this->getPrimaryKey()))
            ->from($this->getTableName());

        $query = new Query($queryBuilder, $this->getDataBase());
        $query->execute();

        return $query->getFirstResult()['count'];
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
     * @throws DataBaseAdapterException
     */
    private function getDataBase(): DataBase
    {
        return DataBaseFactory::getInstance();
    }
}