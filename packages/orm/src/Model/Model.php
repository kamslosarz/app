<?php

namespace Orm\Model;

use Orm\DataBase\DataBase;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\DataBase\DataBaseFactory;
use Orm\OrmException;
use Orm\Peer\Peer;
use Orm\Query\Query;
use Orm\QueryBuilder\QueryBuilder;
use Orm\QueryBuilder\QueryBuilderPeers;

abstract class Model extends Peer
{
    protected array $properties;

    /**
     * Model constructor.
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    abstract protected function getPrimaryKey(): string;

    /**
     * @return string
     */
    abstract protected function getTableName(): string;

    /**
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function save()
    {
        if(isset($this->properties[$this->getPrimaryKey()]) && $this->properties[$this->getPrimaryKey()])
        {
            $this->updateRecord();
        }
        else
        {
            $this->saveRecord();
        }
    }

    /**
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    protected function updateRecord(): void
    {
        $properties = $this->properties;
        unset($properties[$this->getPrimaryKey()]);
        $queryBuilder = $this->getQueryBuilder()
            ->update($this->getTableName())
            ->set($properties)
            ->where(
                $this->getPrimaryKey(),
                QueryBuilderPeers::IS,
                ':primaryKey',
                [':primaryKey' => $this->properties[$this->getPrimaryKey()]]
            );
        $query = new Query($queryBuilder, $this->getDataBase());
        $query->execute();
    }

    /**
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    protected function saveRecord(): void
    {
        $queryBuilder = $this->getQueryBuilder()
            ->insert($this->getTableName())
            ->values($this->properties);

        $query = new Query($queryBuilder, $this->getDataBase());
        $query->execute();
        $this->properties[$this->getPrimaryKey()] = $query->getLastInsertId();
    }

    /**
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function delete(): void
    {
        $queryBuilder = $this->getQueryBuilder()
            ->delete()
            ->from($this->getTableName())
            ->where($this->getPrimaryKey(), QueryBuilderPeers::IS, ':primaryKey', [
                'primaryKey' => $this->properties[$this->getPrimaryKey()]
            ]);

        $query = new Query($queryBuilder, $this->getDataBase());
        $query->execute();
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder();
    }

    /**
     * @return DataBase
     */
    protected function getDataBase(): DataBase
    {
        return DataBaseFactory::getInstance();
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return $this->properties;
    }
}