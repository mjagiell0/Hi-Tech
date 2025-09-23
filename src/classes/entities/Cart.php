<?php

class Cart extends Entity
{
    private $id;
    private $ownerId;

    public function getId()
    {
        return $this->id;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) === 0) {
            throw new InvalidArgumentException('Insufficient criteria for CREATE operation.');
        }
        return "INSERT INTO cart(owner_id) VALUE ?";
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) === 0) {
            throw new InvalidArgumentException('Insufficient criteria for READ operation.');
        }
        return "SELECT id FROM cart WHERE owner_id = ?";
    }

    protected function getUpdateQuery(...$criteria)
    {
        // TODO: Implement getUpdateQuery() method.
    }

    protected function getDeleteQuery(...$criteria)
    {
        // TODO: Implement getDeleteQuery() method.
    }

    protected function fromRow($row)
    {
        return (new Cart())
            ->withId($row[ConstUtils::FIELD_LABEL_ID]);
    }

    public function prepareToDisplay()
    {
        // TODO: Implement prepareToDisplay() method.
    }

    public function getTableName()
    {
        return "cart";
    }
}