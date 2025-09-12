<?php
class RecoveryPassword extends Entity
{
    private $userId;
    private $recoveryToken;
    private $expirationDate;
    private $createdAt;


    public function withUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    private function withRecoveryToken($recoveryToken)
    {
        $this->recoveryToken = $recoveryToken;
        return $this;
    }

    private function withExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    private function withCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function isExpired()
    {
        $now = new DateTime();
        $expiration = new DateTime($this->expirationDate);
        return $now > $expiration;
    }

    public function generateRecoveryToken()
    {
        $this->recoveryToken = bin2hex(random_bytes(16));
        $this->expirationDate = (new DateTime())->modify('+1 hour')->format('Y-m-d H:i:s');
        $this->createdAt = (new DateTime())->format('Y-m-d H:i:s');
    }

    public function getRecoveryToken()
    {
        return $this->recoveryToken;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 4 ||
            !is_int($criteria[0]) ||
            !is_string($criteria[1]) ||
            !is_string($criteria[2]) ||
            !is_string($criteria[3])
        ) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }
        return "INSERT INTO recovery_password (user_id, token, expires_at, created_at) VALUES (?, ?, ?, ?)";
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1 || !is_string($criteria[0])) {
            throw new InvalidArgumentException("Insufficient criteria for READ operation.");
        }
        return "SELECT user_id, token, expires_at, created_at FROM recovery_password WHERE token = ?";
    }

    protected function getUpdateQuery(...$criteria) {}

    protected function getDeleteQuery(...$criteria)
    {
        if (count($criteria) != 1 || !is_int($criteria[0])) {
            throw new InvalidArgumentException("Insufficient criteria for DELETE operation.");
        }
        return "DELETE FROM recovery_password WHERE user_id = ?";
    }

    public function fromResult($result)
    {
        if ($row = $result->fetch_assoc()) {
            return $this
                ->withUserId($row[ConstUtils::FIELD_LABEL_USER_ID])
                ->withRecoveryToken($row[ConstUtils::FIELD_LABEL_RECOVERY_TOKEN])
                ->withExpirationDate($row[ConstUtils::FIELD_LABEL_EXPIRATION_DATE])
                ->withCreatedAt($row[ConstUtils::FIELD_LABEL_CREATED_AT]);
        }
        return null;
    }

    public function getTableName()
    {
        return 'recovery_password';
    }

    public function prepareToDisplay()
    {
        // TODO: Implement prepareToDisplay() method.
    }
}
