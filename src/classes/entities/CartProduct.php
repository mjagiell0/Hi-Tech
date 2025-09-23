<?php

class CartProduct extends Product
{
    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 3
            || intval($criteria[0]) === 0
            || intval($criteria[1]) === 0
            || intval($criteria[2]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }

        return "INSERT INTO cart_item(product_id, quantity, cart_id) VALUES (?, ?, ?);";
    }
}