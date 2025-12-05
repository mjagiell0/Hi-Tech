<?php

class Order extends Entity
{
    private $id;
    private $status;
    private $ownerId;
    private $address;
    private $paymentCard;
    private $createdAt;
    private $modifiedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getPaymentCard()
    {
        return $this->paymentCard;
    }


    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function withOwnerId($owner_id)
    {
        $this->ownerId = $owner_id;
        return $this;
    }

    public function withAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function withPaymentCard($payment_card)
    {
        $this->paymentCard = $payment_card;
        return $this;
    }

    public function withCreatedAt($created_at)
    {
        $this->createdAt = $created_at;
        return $this;
    }

    public function withModifiedAt($modified_at)
    {
        $this->modifiedAt = $modified_at;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 4
            || intval($criteria[0]) === 0
            || intval($criteria[1]) === 0
            || intval($criteria[2]) === 0
            || OrderStatusEnum::tryFrom($criteria[3]) === null) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }
        return "INSERT INTO `order`(owner_id, address_id, payment_card_id, status) VALUES (?,?,?,?)";
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for READ operation.");
        }
        return "SELECT o.id order_id, o.owner_id, o.status, o.created_at, o.modified_at, ad.*, pc.*
                FROM `order` o 
                JOIN address ad ON o.address_id = ad.id 
                JOIN payment_card pc ON o.payment_card_id = pc.id
                WHERE o.owner_id = ?
                ORDER BY o.created_at DESC ";
    }

    protected function getUpdateQuery(...$criteria)
    {
        $date = DateTime::createFromFormat('Y-m-d', $criteria[1]);

        if (count($criteria) != 3
            || OrderStatusEnum::tryFrom($criteria[0]) === null
            || ($date && $date->format('Y-m-d') === $criteria[1])
            || intval($criteria[2]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for UPDATE operation.");
        }

        return "UPDATE `order` SET status = ?, modified_at = ? WHERE id = ?";
    }

    protected function getDeleteQuery(...$criteria)
    {
        // TODO: Implement getDeleteQuery() method.
    }

    protected function fromRow($row)
    {
        return (new Order())
            ->withId($row[ConstUtils::FIELD_LABEL_ORDER_ID])
            ->withStatus($row[ConstUtils::FIELD_LABEL_STATUS])
            ->withOwnerId($row[ConstUtils::FIELD_LABEL_OWNER_ID])
            ->withAddress((new Address())->fromRow($row))
            ->withPaymentCard((new PaymentCard())->fromRow($row))
            ->withCreatedAt($row[ConstUtils::FIELD_LABEL_CREATED_AT])
            ->withModifiedAt($row[ConstUtils::FIELD_LABEL_MODIFIED_AT]);
    }

    public function prepareToDisplay()
    {
        $this->address->prepareToDisplay();
        $this->paymentCard->prepareToDisplay();
    }

    public function getTableName()
    {
        return "order";
    }
}