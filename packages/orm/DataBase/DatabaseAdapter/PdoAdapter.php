<?php

class PdoAdapter implements DatabaseAdapterInterface
{
    protected array $options;
    protected PDO $pdo;
    protected array $results;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return void
     * @throws DataBaseAdapterException
     */
    public function connect(): void
    {
        try
        {
            $this->pdo = new PDO($this->options['dsn'], $this->options['username'] ?? '', $this->options['password'] ?? '', $this->options['options'] ?? []);
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
    private function throwErrorIfOccurred(PDOStatement $stmt): void
    {
        if($stmt->errorCode() !== '0000')
        {
            throw new DataBaseAdapterException($stmt->errorInfo(), $stmt->errorCode());
        }
        elseif($this->pdo->errorCode() !== '0000')
        {
            throw new DataBaseAdapterException($this->pdo->errorInfo(), $this->pdo->errorCode());
        }
    }
}