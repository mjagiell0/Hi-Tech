<?php

class Specification extends Entity
{
    private $id;
    private $key;
    private $value;
    private $productId;

    public function getId()
    {
        return $this->id;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function withValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function withProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        // TODO: Implement getCreateQuery() method.
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) !== 1
            || intval($criteria[0]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for READ operation.");
        }

        return "SELECT `id`, `key`, `value`, `product_id` 
                FROM `specification` 
                WHERE product_id = ?";
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
        return (new Specification())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withKey($row[ConstUtils::FIELD_LABEL_KEY])
            ->withValue($row[ConstUtils::FIELD_LABEL_VALUE])
            ->withProductId($row[ConstUtils::FIELD_LABEL_PRODUCT_ID]);
    }

    public function prepareToDisplay()
    {
        $this->key = htmlspecialchars($this->getKey());
        $this->value = htmlspecialchars($this->getValue());
    }

    public function getTableName()
    {
        return "specification";
    }
}