<?php

abstract class Entity {
    public function getQuery(CrudEnum $crudType, ...$criteria) {
        switch ($crudType) {
            case CrudEnum::CREATE:
                return $this->getCreateQuery(...$criteria);
            case CrudEnum::READ:
                return $this->getReadQuery(...$criteria);
            case CrudEnum::UPDATE:
                return $this->getUpdateQuery(...$criteria);
            case CrudEnum::DELETE:
                return $this->getDeleteQuery(...$criteria);
            default:
                throw new InvalidArgumentException("Invalid CRUD operation type.");
        }
    }

    abstract protected function getCreateQuery(...$criteria);

    abstract protected function getReadQuery(...$criteria);

    abstract protected function getUpdateQuery(...$criteria);
    
    abstract protected function getDeleteQuery(...$criteria);

    abstract public function prepareToDisplay();

    abstract public function fromResult($result);

    abstract public function getTableName();
}
