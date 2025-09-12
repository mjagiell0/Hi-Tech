<?php

class DatabaseHandler {
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

    public function __construct($url, $username, $password, $database, $port = 3306) {
        $this->connection = new mysqli($url, $username, $password, $database, $port);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function __destruct() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function query(Entity $entity, CrudEnum $crudType, ...$criteria) {
        $query = $entity->getQuery($crudType, ...$criteria);

        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }

        if ($criteria) {
            $stmt->bind_param(str_repeat('s', count($criteria)), ...$criteria);
        }

        $stmt->execute();

        return $crudType === CrudEnum::READ ? $entity->fromResult($stmt->get_result()) : null;
    }
}
