<?php

class Address extends Entity
{
    private $id;
    private $ownerId;
    private $city;
    private $street;
    private $postalCode;
    private $houseNumber;

    public function getId()
    {
        return $this->id;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    public function withCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function withStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    public function withPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function withHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) !== 5
            || intval($criteria[0]) === 0
            || !is_string($criteria[1])
            || !is_string($criteria[2])
            || !is_string($criteria[3])
            || !is_string($criteria[4])) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }
        return "INSERT INTO address(owner_id, city, street, postal_code, house_number) 
                VALUES (?,?,?,?,?)";
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) !== 1
            || intval($criteria[0]) === 0) {
            if (count($criteria) !== 2
                || intval($criteria[0]) === 0
                || intval($criteria[1]) === 0) {
                throw new InvalidArgumentException("Insufficient criteria for READ operation.");
            }
            return "SELECT * 
                    FROM address 
                    WHERE owner_id = ? 
                    AND id = ?";
        }

        return "SELECT * 
                FROM address 
                WHERE owner_id = ?";
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
        return (new Address())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withOwnerId($row[ConstUtils::FIELD_LABEL_OWNER_ID])
            ->withCity($row[ConstUtils::FIELD_LABEL_CITY])
            ->withStreet($row[ConstUtils::FIELD_LABEL_STREET])
            ->withHouseNumber($row[ConstUtils::FIELD_LABEL_HOUSE_NUMBER])
            ->withPostalCode($row[ConstUtils::FIELD_LABEL_POSTAL_CODE]);
    }

    public function toString()
    {
        return "ul. ".
            $this->street.
            " ".
            $this->houseNumber.
            ", ".
            $this->city;
    }

    public function prepareToDisplay()
    {
        $this->city = htmlspecialchars($this->city);
        $this->street = htmlspecialchars($this->street);
        $this->houseNumber = htmlspecialchars($this->houseNumber);
        $this->postalCode = htmlspecialchars($this->postalCode);
    }

    public function getTableName()
    {
        return "address";
    }
}