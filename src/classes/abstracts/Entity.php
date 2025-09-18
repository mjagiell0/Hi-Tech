<?php

abstract class Entity {
    public function getQuery(CrudEnum $crudType, ...$criteria) {
        return match ($crudType) {
            CrudEnum::CREATE => $this->getCreateQuery(...$criteria),
            CrudEnum::READ => $this->getReadQuery(...$criteria),
            CrudEnum::UPDATE => $this->getUpdateQuery(...$criteria),
            CrudEnum::DELETE => $this->getDeleteQuery(...$criteria),
        };
    }

    public function getResults($results)
    {
        $objects = [];

        while ($row = $results->fetch_assoc()) {
            $objects[] = $this->fromRow($row);
        }

        return empty($objects) ? null : $objects;
    }

    abstract protected function getCreateQuery(...$criteria);

    abstract protected function getReadQuery(...$criteria);

    abstract protected function getUpdateQuery(...$criteria);
    
    abstract protected function getDeleteQuery(...$criteria);

    abstract protected function fromRow($row);

    abstract public function prepareToDisplay();

    abstract public function getTableName();
}
