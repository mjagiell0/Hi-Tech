<?php

class ProductFortune extends Entity
{
    private $id;
    private $product_id;
    private $name;
    private $image_path;
    private $price;
    private $discount;
    private $user_id;
    private $createdAt;
    private $expiresAt;

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 5
            || intval($criteria[0]) == 0
            || intval($criteria[1]) == 0
            || doubleval($criteria[2]) == 0
            || !is_string($criteria[3])
            || !is_string($criteria[4])) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }

        return "INSERT INTO `user_spin_rewards`(`user_id`, `product_id`, `discount_percent`, `created_at`, `expires_at`) VALUES (?,?,?,?,?)";
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1
        || intval($criteria[0]) == 0) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }

        return "SELECT sr.id ,sr.product_id product_id, p.name, pi.path, p.price, sr.percent, sr.created_at, sr.expires_at  
                FROM user_spin_rewards sr 
                JOIN product p ON p.id = sr.product_id
                LEFT JOIN product_image pi ON sr.product_id = pi.product_id AND pi.is_default = 1
                WHERE sr.user_id = ?";
    }

    protected function getUpdateQuery(...$criteria)
    {
        // TODO: Implement getUpdateQuery() method.
    }

    protected function getDeleteQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) == 0) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }

        return "DELETE FROM user_spin_rewards WHERE user_id = ?";
    }

    public function prepareToDisplay()
    {
        // TODO: Implement prepareToDisplay() method.
    }

    public function fromResult($result)
    {
        // TODO: Implement fromResult() method.
    }

    public function getTableName()
    {
        return 'user_spin_rewards';
    }
}