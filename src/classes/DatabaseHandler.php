<?php

class DatabaseHandler {
    private $connection;

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

    public function query(Querable $querable, ...$criteria) {
        $query = $querable->getQuery(...$criteria);

        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }

        if ($criteria) {
            $stmt->bind_param(str_repeat('s', count($criteria)), ...$criteria);
        }

        $stmt->execute();

        return $querable->fromResult($stmt->get_result());
    }
}
