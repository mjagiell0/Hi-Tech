<?php

class ProductImage extends Entity {
    private $id;
    private $productId;
    private $imagePath;
    private $isDefault;

    public function getId()
    {
        return $this->id;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getImagePath()
    {
        return $this->imagePath;
    }

    public function isDefault() {
        return $this->isDefault;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    public function withImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function withIsDefault($isDefault) {
        $this->isDefault = $isDefault;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        // TODO: Implement getCreateQuery() method.
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for READ operation.");
        }

        return "SELECT id, product_id, path image_name, is_default FROM product_image WHERE product_id = ?";
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
        return (new ProductImage())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withProductId($row[ConstUtils::FIELD_LABEL_PRODUCT_ID])
            ->withImagePath($row[ConstUtils::FIELD_LABEL_IMAGE_NAME]);
    }

    public function prepareToDisplay()
    {
        $this->imagePath = htmlspecialchars($this->getImagePath());
    }

    public function getTableName()
    {
        return "product_image";
    }
}