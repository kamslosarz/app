<?php

class DataBase
{
    protected DatabaseAdapterInterface $databaseAdapter;
    protected array $errors;

    public function __construct(DatabaseAdapterInterface $databaseAdapter)
    {
        $this->databaseAdapter = $databaseAdapter;
    }

    public function query(string $query, array $bind): ?array
    {
        try
        {
            $this->databaseAdapter->query($query, $bind);

            return $this->databaseAdapter->getResults();
        }
        catch(DataBaseAdapterException $dataBaseAdapterException)
        {
            $this->errors[] = sprintf('%s:%s', $dataBaseAdapterException->getMessage(), $dataBaseAdapterException->getTraceAsString());

            return null;
        }
    }

    public function hasErrors(): bool
    {
        return empty($this->errors);
    }

    public function getErrors(): string
    {
        return implode(', ', $this->errors);
    }
}