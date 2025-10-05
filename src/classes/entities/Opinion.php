<?php

class Opinion extends Entity
{
    private $id;
    private $stars;
    private $comment;
    private $ownerFirstname;
    private $ownerLastname;
    private $product;
    private $producent;
    private $productId;
    private $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function getStars()
    {
        return $this->stars;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getOwnerFirstname()
    {
        return $this->ownerFirstname;
    }

    public function getOwnerLastname()
    {
        return $this->ownerLastname;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function getProducent()
    {
        return $this->producent;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getFormatedCreatedAt()
    {
        return date('d.m.Y', strtotime($this->createdAt));
    }

    public function getAuthor()
    {
        return $this->ownerFirstname . " " . $this->ownerLastname;
    }

    public function withProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    public function withProducent($producent)
    {
        $this->producent = $producent;
        return $this;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withStars($stars)
    {
        $this->stars = $stars;
        return $this;
    }

    public function withComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function withOwnerFirstname($ownerFirstname)
    {
        $this->ownerFirstname = $ownerFirstname;
        return $this;
    }

    public function withOwnerLastname($ownerLastname)
    {
        $this->ownerLastname = $ownerLastname;
        return $this;
    }

    public function withProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }
    
    public function withCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        // TODO: Implement getCreateQuery() method.
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) !== 0) {
            if (count($criteria) !== 1
                || intval($criteria[0]) === 0) {
                throw new Exception("Insufficient criteria for READ operation.");
            }
            return "SELECT op.id, op.stars, op.comment, u.firstname, u.lastname, p.name, p.producent, p.id product_id, op.created_at
                FROM opinion op 
                    JOIN product p ON p.id = op.product_id 
                    JOIN user u ON u.id = op.owner_id 
                WHERE op.product_id = ?";
        }

        $MIN_STARS_VALUE = ConstUtils::MIN_STARS_VALUE;

        return "SELECT op.id, op.stars, op.comment, u.firstname, u.lastname, p.name, p.producent, p.id product_id
                FROM opinion op 
                    JOIN product p ON p.id = op.product_id 
                    JOIN user u ON u.id = op.owner_id 
                WHERE op.stars >= $MIN_STARS_VALUE";
    }

    protected function getUpdateQuery(...$criteria)
    {
        // TODO: Implement getUpdateQuery() method.
    }

    protected function getDeleteQuery(...$criteria)
    {
        // TODO: Implement getDeleteQuery() method.
    }

    public function fromRow($row)
    {
        return (new Opinion())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withComment($row[ConstUtils::FIELD_LABEL_COMMENT])
            ->withStars($row[ConstUtils::FIELD_LABEL_STARS])
            ->withOwnerFirstname($row[ConstUtils::FIELD_LABEL_FIRSTNAME])
            ->withOwnerLastname($row[ConstUtils::FIELD_LABEL_LASTNAME])
            ->withProduct($row[ConstUtils::FIELD_LABEL_NAME])
            ->withProducent($row[ConstUtils::FIELD_LABEL_PRODUCENT])
            ->withProductId($row[ConstUtils::FIELD_LABEL_PRODUCT_ID])
            ->withCreatedAt($row[ConstUtils::FIELD_LABEL_CREATED_AT]);
    }

    public function getTableName()
    {
        return "opinion";
    }

    public function prepareToDisplay()
    {
        $this->product = htmlspecialchars($this->product);
        $this->comment = htmlspecialchars($this->comment);
        $this->producent = htmlspecialchars($this->producent);
        $this->ownerFirstname = htmlspecialchars($this->ownerFirstname);
        $this->ownerLastname = htmlspecialchars($this->ownerLastname);
        $this->createdAt = htmlspecialchars($this->createdAt);
    }
}