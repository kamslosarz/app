<?php

class DataBaseFactory
{
    public function getInstance(array $config = [])
    {
        return new DataBase([
            'pdo' => [
                'dns' => 'sqlite::memory:',
                'username' => null,
                'password' => null,
                'options' => null
            ]
        ]);
    }
}