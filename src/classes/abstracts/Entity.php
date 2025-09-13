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

    abstract protected function getCreateQuery(...$criteria);

    abstract protected function getReadQuery(...$criteria);

    abstract protected function getUpdateQuery(...$criteria);
    
    abstract protected function getDeleteQuery(...$criteria);

    abstract public function prepareToDisplay();

    abstract public function fromResult($result);

    abstract public function getTableName();
}
