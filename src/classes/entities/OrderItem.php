<?php

class OrderItem extends Product
{
    private $quantity;
    private $orderId;

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function withQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function withOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 3
            || intval($criteria[0]) === 0
            || intval($criteria[1]) === 0
            || intval($criteria[2]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }
        return "INSERT INTO order_item (product_id, quantity, order_id) VALUES (?, ?, ?)";
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
            return "SELECT p.id, p.name, oi.quantity, oi.order_id, p.price, p.stock_quantity, pi.path image_name, p.producent
                FROM order_item oi
                LEFT JOIN product_image pi ON pi.product_id = oi.product_id AND pi.is_default = 1
                JOIN product p ON oi.product_id = p.id
                WHERE oi.product_id IN (?) AND oi.order_id = ?;";
        }
        return "SELECT p.id, p.name, oi.quantity, oi.order_id, p.price, pi.path image_name, p.producent
                FROM order_item oi
                LEFT JOIN product_image pi ON pi.product_id = oi.product_id AND pi.is_default = 1
                JOIN product p ON oi.product_id = p.id
                WHERE oi.order_id = ?;";
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
        return (new OrderItem())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withProductName($row[ConstUtils::FIELD_LABEL_NAME])
            ->withPrice($row[ConstUtils::FIELD_LABEL_PRICE])
            ->withQuantity($row[ConstUtils::FIELD_LABEL_QUANTITY])
            ->withImageName(is_null($row[ConstUtils::FIELD_LABEL_IMAGE_NAME]) ? ConstUtils::DEFAULT_IMAGE : $row[ConstUtils::FIELD_LABEL_IMAGE_NAME])
            ->withProducent($row[ConstUtils::FIELD_LABEL_PRODUCENT])
            ->withOrderId($row[ConstUtils::FIELD_LABEL_ORDER_ID]);
    }

    public function prepareToDisplay()
    {
        parent::prepareToDisplay();
    }

    public function fromCartProduct($cartProduct)
    {
        return (new OrderItem())
            ->withProductName($cartProduct->getProductName())
            ->withPrice($cartProduct->getPrice())
            ->withQuantity($cartProduct->getQuantity())
            ->withImageName($cartProduct->getImageName())
            ->withProducent($cartProduct->getProducent());
    }

    public function getTableName()
    {
        return 'order_item';
    }
}