<?php

class PaymentCard extends Entity
{
    private $id;
    private $userId;
    private $cardholderName;
    private $cardNumberLast4;
    private $expirationMonth;
    private $expirationYear;
    private $cvv;
    private $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getCardholderName()
    {
        return $this->cardholderName;
    }

    public function getCardNumberLast4()
    {
        return $this->cardNumberLast4;
    }

    public function getExpirationMonth()
    {
        return $this->expirationMonth;
    }

    public function getExpirationYear()
    {
        return $this->expirationYear;
    }

    public function getCvv()
    {
        return $this->cvv;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function withCardholderName($cardholderName)
    {
        $this->cardholderName = $cardholderName;
        return $this;
    }

    public function withCardNumberLast4($cardNumberLast4)
    {
        $this->cardNumberLast4 = $cardNumberLast4;
        return $this;
    }

    public function withExpirationMonth($expirationMonth)
    {
        $this->expirationMonth = $expirationMonth;
        return $this;
    }

    public function withExpirationYear($expirationYear)
    {
        $this->expirationYear = $expirationYear;
        return $this;
    }

    public function withCvv($cvv)
    {
        $this->cvv = $cvv;
        return $this;
    }

    public function withCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) !== 6
            || intval($criteria[0]) === 0
            || !is_string($criteria[1])
            || strlen($criteria[2]) !== 4
            || intval($criteria[3]) === 0
            || intval($criteria[4]) === 0
            || intval($criteria[5]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }

        return "INSERT INTO payment_card(user_id, cardholder_name, card_number_last_4, expiration_month, expiration_year, cvv) 
                values (?,?,?,?,?,?)";
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) !== 1
            || intval($criteria[0]) === 0) {
            if (count($criteria) !== 2
                || intval($criteria[0]
                    || intval($criteria[2])) === 0) {
                throw new InvalidArgumentException("Insufficient criteria for READ operation.");
            }

            return "SELECT * FROM payment_card WHERE user_id = ? AND id = ?";
        }

        return "SELECT * FROM payment_card WHERE user_id = ? ORDER BY created_at DESC";
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
        return (new PaymentCard())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withUserId($row[ConstUtils::FIELD_LABEL_USER_ID])
            ->withCardholderName($row[ConstUtils::FIELD_LABEL_CARDHOLDER_NAME])
            ->withCardNumberLast4($row[ConstUtils::FIELD_LABEL_CARD_NUMBER_LAST_4])
            ->withExpirationMonth($row[ConstUtils::FIELD_LABEL_EXPIRATION_MONTH])
            ->withExpirationYear($row[ConstUtils::FIELD_LABEL_EXPIRATION_YEAR])
            ->withCvv($row[ConstUtils::FIELD_LABEL_CVV])
            ->withCreatedAt($row[ConstUtils::FIELD_LABEL_CREATED_AT]);
    }

    public function prepareToDisplay()
    {
        $this->withCardholderName(htmlspecialchars($this->cardholderName));
        $this->withCardNumberLast4(htmlspecialchars($this->cardNumberLast4));
        $this->withExpirationMonth(htmlspecialchars($this->expirationMonth));
        $this->withExpirationYear(htmlspecialchars($this->expirationYear));
    }

    public function getTableName()
    {
        return 'payment_card';
    }
}