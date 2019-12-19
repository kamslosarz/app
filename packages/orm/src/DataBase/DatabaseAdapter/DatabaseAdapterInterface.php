<?php

namespace Orm\DataBase\DatabaseAdapter;

interface DatabaseAdapterInterface
{
    public function __construct(array $options);

    /**
     * @throws DataBaseAdapterException
     */
    public function connect(): void;

    /**
     * @throws DataBaseAdapterException
     */
    public function query(string $query, array $binds): void;

    /**
     * @return int
     */
    public function getLastInsertId(): int;

    /**
     * @return mixed
     */
    public function getResults();
}