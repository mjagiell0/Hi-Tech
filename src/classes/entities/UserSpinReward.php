<?php

class UserSpinReward extends Entity
{
    private $id;
    private $user_id;
    private $created_at;
    private $expires_at;
    private $reward_id;

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
        // TODO: Implement getReadQuery() method.
    }

    protected function getUpdateQuery(...$criteria)
    {
        // TODO: Implement getUpdateQuery() method.
    }

    protected function getDeleteQuery(...$criteria)
    {
        // TODO: Implement getDeleteQuery() method.
    }

    public function prepareToDisplay()
    {
        // TODO: Implement prepareToDisplay() method.
    }

    public function fromRow($row)
    {
        $objects = [];

        while ($row = $row->fetch_assoc()) {
            $objects[] = (new Section())
                ->withId($row[ConstUtils::FIELD_LABEL_ID])
                ->withName($row[ConstUtils::FIELD_LABEL_NAME]);
        }

        return empty($objects) ? null : $objects;
    }

    public function getTableName()
    {
        // TODO: Implement getTableName() method.
    }
}