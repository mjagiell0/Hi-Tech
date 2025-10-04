<?php

class ProductDiscount extends Entity
{
    private $id;
    private $name;
    private $producent;
    private $price;
    private $imageName;
    private $discount;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getProducent()
    {
        return $this->producent;
    }

    public function getPrice()
    {
        return number_format($this->price, 2, ',', '') . ' zł';
    }

    public function getImageName()
    {
        return $this->imageName;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function getPriceAfterDiscount()
    {
        $discountedPrice = $this->price - ($this->price * $this->discount);
        return number_format($discountedPrice, 2, ',', '') . ' zł';
    }


    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function withProducent($producent)
    {
        $this->producent = $producent;
        return $this;
    }

    public function withPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function withImageName($imageName)
    {
        $this->imageName = $imageName;
        return $this;
    }

    public function withDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        // TODO: Implement getCreateQuery() method.
    }

    protected function getReadQuery(...$criteria)
    {
        return "SELECT p.id id, p.name name, p.producent producent, p.price price, d.percent discount, pi.path image_name
                FROM product p 
                JOIN discount d ON p.id = d.product_id 
                LEFT JOIN product_image pi ON pi.product_id = p.id AND pi.is_default = TRUE
                ORDER BY d.percent DESC";
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
        return(new ProductDiscount())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withName($row[ConstUtils::FIELD_LABEL_NAME])
            ->withProducent($row[ConstUtils::FIELD_LABEL_PRODUCENT])
            ->withDiscount($row[ConstUtils::FIELD_LABEL_DISCOUNT])
            ->withPrice($row[ConstUtils::FIELD_LABEL_PRICE])
            ->withImageName(is_null($row[ConstUtils::FIELD_LABEL_IMAGE_NAME]) ? ConstUtils::DEFAULT_IMAGE : $row[ConstUtils::FIELD_LABEL_IMAGE_NAME]);
    }

    public function getTableName()
    {
        return "product";
    }

    public function prepareToDisplay()
    {
        $this->name = htmlspecialchars($this->name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->imageName = htmlspecialchars($this->imageName, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->producent = htmlspecialchars($this->producent, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}