<?php

interface Querable {
    public function getQuery(...$criteria);

    public function fromResult($row);

    public function getTableName();
}
