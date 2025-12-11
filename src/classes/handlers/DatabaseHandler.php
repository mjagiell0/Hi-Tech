<?php

class DatabaseHandler
{
    private $connection;

    public static function getDbHandler()
    {
        return new DatabaseHandler(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            $_ENV['DB_NAME']
        );
    }

    public function __construct($url, $username, $password, $database, $port = 3306)
    {
        $this->connection = new mysqli($url, $username, $password, $database, $port);

        if ($this->connection->connect_error) {
            throw new RuntimeException("Connection failed: " . $this->connection->connect_error);
        }
    }

//    public function __destruct()
//    {
//        if ($this->connection) {
//            $this->connection->close();
//        }
//    }

    public function beginTransaction()
    {
        $this->connection->begin_transaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollback()
    {
        $this->connection->rollback();
    }

    public function query(Entity $entity, CrudEnum $crudType, ...$criteria)
    {
        $query = $entity->getQuery($crudType, ...$criteria);

        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }

        if ($criteria) {
            $stmt->bind_param(str_repeat('s', count($criteria)), ...$criteria);
        }

        $stmt->execute();

        if ($crudType === CrudEnum::READ) {
            $results = $entity->getResults($stmt->get_result());
            if (!is_null($results)) {
                if (count($results) === 1) {
                    return $results[0];
                } else {
                    return $results;
                }
            }
        }

        return null;
    }
}
