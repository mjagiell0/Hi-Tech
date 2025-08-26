<?php
class RecoveryPassword extends Entity {
    private $userId;
    private $recoveryToken;
    private $expirationDate;
    private $createdAt;


    public function withUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    private function withRecoveryToken($recoveryToken) {
        $this->recoveryToken = $recoveryToken;
        return $this;
    }

    private function withExpirationDate($expirationDate) {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    private function withCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function generateRecoveryToken() {
        $this->recoveryToken = bin2hex(random_bytes(16));
        $this->expirationDate = (new DateTime())->modify('+1 hour')->format('Y-m-d H:i:s');
        $this->createdAt = (new DateTime())->format('Y-m-d H:i:s');
    }

    public function getRecoveryToken() {
        return $this->recoveryToken;
    }

    public function getExpirationDate() {
        return $this->expirationDate;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    protected function getCreateQuery(...$criteria) {
        if (count($criteria) < 4) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }
        return "INSERT INTO recovery_password (user_id, token, expires_at, created_at) VALUES (?, ?, ?, ?)";
    }

    protected function getReadQuery(...$criteria) {
        if (count($criteria) != 1) {
            throw new InvalidArgumentException("Insufficient criteria for READ operation.");
        }
        return "SELECT user_id, token, expires_at, created_at FROM recovery_password WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
    }

    protected function getUpdateQuery(...$criteria) {

    }

    protected function getDeleteQuery(...$criteria) {

    }

    public function fromResult($result) {
        return null;
    }

    public function getTableName() {
        return 'recovery_password';
    }
}
