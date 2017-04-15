<?php

namespace App\Repositories;

/**
 * Class BettingRepository
 * @package App\Repositories
 */
class Repository
{
    /**
     * @var \PDO
     */
    private $connection;

    /**
     * BettingRepository constructor.
     */
    public function __construct()
    {
        $config = parse_ini_file(__DIR__ .'/../../config/db.ini');

        $this->connection = new \PDO("{$config['DB_DRIVER']}:host={$config['DB_HOST']};dbname={$config['DB_DATABASE']}", $config['DB_USERNAME'], $config['DB_PASSWORD']);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function insert($query, array $data)
    {
        $this->connection->prepare($query)->execute($data);
        return $this->connection->lastInsertId();
    }

    public function update($query, array $data)
    {
        $stmt = $this->executeQuery($query, $data);
        return $stmt->rowCount();
    }

    public function delete($query, array $data)
    {
        $stmt = $this->executeQuery($query, $data);
        return $stmt->rowCount();
    }

    public function findOne($query, array $data = null)
    {
        $stmt = $this->executeQuery($query, $data);
        return $stmt->fetchObject();
    }

    public function findMany($query, array $data = null)
    {
        $stmt = $this->executeQuery($query, $data);
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function executeQuery($query, $data = null)
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($data);
        return $stmt;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
