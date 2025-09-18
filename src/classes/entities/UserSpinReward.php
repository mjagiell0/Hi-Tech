<?php

class UserSpinReward extends Entity
{
    private $id;
    private $user_id;
    private $created_at;
    private $expires_at;
    private $reward_id;

    private $reward;

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getExpiresAt()
    {
        return $this->expires_at;
    }

    public function getRewardId()
    {
        return $this->reward_id;
    }

    public function getProductReward()
    {
        return $this->reward;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function withCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function withExpiresAt($expires_at)
    {
        $this->expires_at = $expires_at;
        return $this;
    }

    public function withRewardId($reward_id)
    {
        $this->reward_id = $reward_id;
        return $this;
    }

    public function withProductReward($reward)
    {
        $this->reward = $reward;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 4
            || intval($criteria[0]) == 0
            || intval($criteria[1]) == 0
            || !is_string($criteria[2])
            || !is_string($criteria[3])
        ) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }
        return "INSERT INTO `user_spin_rewards`(`user_id`, `reward_id`, `created_at`, `expires_at`) VALUES(?,?,?,?)";
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) == 0) {
            throw new InvalidArgumentException("Insufficient criteria for READ operation.");
        }
        return "SELECT rp.id, rp.product_id, rp.rare_rate, rp.percent, p.name, p.price, pi.path image_name
                FROM `rewardable_products` rp
                JOIN user_spin_rewards usr ON usr.reward_id = rp.id
                JOIN product p ON p.id = rp.product_id
                LEFT JOIN product_image pi ON pi.product_id = rp.product_id AND pi.is_default = 1
                WHERE usr.user_id = ?";
    }

    protected function getUpdateQuery(...$criteria)
    {
        // TODO: Implement getUpdateQuery() method.
    }

    protected function getDeleteQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) == 0) {
            throw new InvalidArgumentException("Insufficient criteria for DELETE operation.");
        }

        return "DELETE FROM user_spin_rewards WHERE user_id = ?";
    }

    public function prepareToDisplay()
    {
        $this->reward->prepareToDisplay();
    }

    public function fromRow($row)
    {
        return $this
            ->withProductReward((new ProductRewardable())->fromRow($row));

    }

    public function getTableName()
    {
        return "user_spin_rewards";
    }
}