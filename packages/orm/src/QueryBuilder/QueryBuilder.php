<?php

namespace Orm\QueryBuilder;

use Orm\OrmException;

class QueryBuilder
{
    protected string $queryType;
    protected string $table;
    protected string $where;
    protected array $binds = [];
    protected array $data;
    protected string $columns;

    /**
     * @param string $table
     * @return $this
     */
    public function insert(string $table): self
    {
        $this->table = $table;
        $this->queryType = QueryBuilderPeers::INSERT;

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function values(array $data): self
    {
        $this->data = array_keys($data);
        $binds = [];
        foreach($data as $name => $value)
        {
            $binds[":$name"] = $value;
        }
        $this->binds += $binds;

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function update(string $table): self
    {
        $this->table = $table;
        $this->queryType = QueryBuilderPeers::UPDATE;

        return $this;
    }

    /**
     * @param string $column
     * @param string $conditionPeer
     * @param $value
     * @param array $binds
     * @return $this
     */
    public function where(string $column, string $conditionPeer, $value, array $binds = []): self
    {
        $this->where = $column . $conditionPeer . $value;
        $this->binds += $binds;

        return $this;
    }


    /**
     * @param array $data
     * @return $this
     */
    public function set(array $data): self
    {
        $this->data = array_keys($data);
        $binds = [];
        foreach($data as $name => $value)
        {
            $binds[":$name"] = $value;
        }
        $this->binds += $binds;

        return $this;
    }

    public function select(string $columns): self
    {
        $this->columns = $columns;
        $this->queryType = QueryBuilderPeers::SELECT;

        return $this;
    }

    public function from(string $table): self
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return array
     */
    public function getBinds(): array
    {
        return $this->binds;
    }

    /**
     * @return string
     * @throws OrmException
     */
    public function build(): string
    {
        $query = '';
        switch($this->queryType)
        {
            case QueryBuilderPeers::UPDATE:
                $sets = '';
                foreach($this->data as $name => $bindName)
                {
                    $sets[] = sprintf('`%s`=:%s', $name, $name);
                }
                $query = sprintf('UPDATE %s SET %s WHERE %s', $this->table, implode(', ', $sets), $this->where);

                break;

            case QueryBuilderPeers::INSERT:
                $values = array_keys($this->binds);
                $query = sprintf('INSERT INTO %s (%s) VALUES (%s)', $this->table, implode(',', $this->data), implode(', ', $values));

                break;

            case QueryBuilderPeers::SELECT:
                $where = '';
                if(isset($this->where))
                {
                    $where = sprintf('WHERE %s', $this->where);
                }
                $query = sprintf("SELECT %s FROM %s %s", $this->columns, $this->table, $where);
                break;

            default:
                throw new OrmException('Invalid query type %s', $this->queryType);
                break;
        }


        return $query;
    }
}