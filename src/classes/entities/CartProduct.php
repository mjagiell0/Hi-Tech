<?php

if (!class_exists('Product')) {
    include_once 'Product.php';
}


class CartProduct extends Product
{
    private $quantity;

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function withQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPriceWithQuantity()
    {
        return number_format($this->getPriceValue() * $this->quantity, 2, ',','');
    }

    public function getPriceWithDiscountAndQuantity()
    {
        return number_format($this->getPriceWithDiscountValue() * $this->quantity, 2, ',','');
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 3
            || intval($criteria[0]) === 0
            || intval($criteria[1]) === 0
            || intval($criteria[2]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }

        return "INSERT INTO cart_item(quantity, product_id, owner_id) VALUES (?, ?, ?);";
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) === 0) {
            if (count($criteria) != 2
                || !is_string($criteria[0])
                || intval($criteria[1]) === 0) {
                throw new InvalidArgumentException("Insufficient criteria for READ operation.");
            }
            return "SELECT p.id, p.name, ci.quantity, p.price, p.stock_quantity, pi.path image_name, p.producent
                FROM cart_item ci
                LEFT JOIN product_image pi ON pi.product_id = ci.product_id AND pi.is_default = 1
                JOIN product p ON ci.product_id = p.id
                WHERE ci.product_id IN (?) AND ci.owner_id = ?;";
        }
        return "SELECT p.id, p.name, ci.quantity, p.price, p.stock_quantity, pi.path image_name, p.producent
                FROM cart_item ci
                LEFT JOIN product_image pi ON pi.product_id = ci.product_id AND pi.is_default = 1
                JOIN product p ON ci.product_id = p.id
                WHERE ci.owner_id = ?;";
    }

    protected function getUpdateQuery(...$criteria)
    {
        if (count($criteria) != 3
            || intval($criteria[0]) === 0
            || intval($criteria[1]) === 0
            || intval($criteria[2]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for UPDATE operation.");
        }
        return "UPDATE cart_item
                SET quantity = ? 
                WHERE product_id = ? AND owner_id = ?;";
    }

    protected
    function getDeleteQuery(...$criteria)
    {
        if (count($criteria) != 2
            || intval($criteria[0]) === 0
            || intval($criteria[1]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for DELETE operation.");
        }

        return "DELETE FROM cart_item WHERE product_id = ? AND owner_id = ?;";
    }

    public
    function fromRow($row)
    {
        return (new CartProduct())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withProductName($row[ConstUtils::FIELD_LABEL_NAME])
            ->withStockQuantity($row[ConstUtils::FIELD_LABEL_QUANTITY])
            ->withPrice($row[ConstUtils::FIELD_LABEL_PRICE])
            ->withStockQuantity($row[ConstUtils::FIELD_LABEL_STOCK_QUANTITY])
            ->withQuantity($row[ConstUtils::FIELD_LABEL_QUANTITY])
            ->withImageName(is_null($row[ConstUtils::FIELD_LABEL_IMAGE_NAME]) ? 'default.png' : $row[ConstUtils::FIELD_LABEL_IMAGE_NAME])
            ->withProducent($row[ConstUtils::FIELD_LABEL_PRODUCENT]);
    }

    public
    function prepareToDisplay()
    {
        $this
            ->withProductName(htmlspecialchars($this->getProductName()))
            ->withImageName(htmlspecialchars($this->getImageName()));
    }
}