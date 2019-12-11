<?php


interface DatabaseAdapterInterface
{
    public function __construct(array $options);

    public function connect(): void;

    public function query(string $query, array $binds): void;

    public function getLastInsertId(): int;

    public function getResults();
}