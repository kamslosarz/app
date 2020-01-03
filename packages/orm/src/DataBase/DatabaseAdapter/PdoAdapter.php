<?php

namespace Orm\DataBase\DatabaseAdapter;

use PDO;
use PDOException;
use PDOStatement;

class PdoAdapter implements DatabaseAdapterInterface
{
    protected array $options;
    protected ?PDO $pdo;
    protected array $results;

    /**
     * PdoAdapter constructor.
     * @param array $options
     * @throws DataBaseAdapterException
     */
    public function __construct(array $options)
    {
        $this->options = $options;
        $this->connect();
    }

    /**
     * @throws DataBaseAdapterException
     */
    public function connect(): void
    {
        try
        {
            $this->pdo = new PDO($this->options['dsn'], $this->options['username'] ?? '',
                $this->options['password'] ?? '', $this->options['options'] ?? []);
        }
        catch(PDOException $PDOException)
        {
            throw new DataBaseAdapterException($PDOException->getMessage(), $PDOException->getCode(), $PDOException);
        }
    }

    /**
     * @param string $query
     * @param array $binds
     * @throws DataBaseAdapterException
     */
    public function query(string $query, array $binds): void
    {
        try
        {
            /** @var PDOStatement $stmt */
            $stmt = $this->pdo->prepare($query);
            $this->throwErrorIfOccurred();
            $stmt->execute($binds);
            $this->throwErrorIfOccurred($stmt);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!is_array($results))
            {
                $this->results = [$results];
            }
            else
            {
                $this->results = $results;
            }
        }
        catch(PDOException $PDOException)
        {
            throw new DataBaseAdapterException($PDOException->getMessage(), $PDOException->getCode(), $PDOException);
        }
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @param PDOStatement $stmt
     * @throws DataBaseAdapterException
     */
    private function throwErrorIfOccurred(PDOStatement $stmt = null): void
    {
        if($stmt && $stmt->errorCode() !== '00000')
        {
            throw new DataBaseAdapterException(implode(', ', $stmt->errorInfo()), (int)$stmt->errorCode(), null);
        }
        elseif($this->pdo->errorCode() !== '00000')
        {
            throw new DataBaseAdapterException(implode(', ', $this->pdo->errorInfo()), (int)$this->pdo->errorCode(), null);
        }
    }
}