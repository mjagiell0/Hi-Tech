<?php

class ProductRewardable extends Entity {
    private $id;
    private $product_id;
    private $rare_rate;
    private $percent;

    private $name;
    private $price;
    private $image_path;

    public function getId()
    {
        return $this->id;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function getRareRate(){
        return $this->rare_rate;
    }

    public function getPercent(){
        return ($this->percent * 100).'%' ;
    }

    public function getName(){
        return $this->name;
    }

    public function getPrice()
    {
        return number_format($this->price, 2, ',', '') . ' zł';
    }

    public function getImageName(){
        return $this->image_path;
    }

    public function getPriceAfterDiscount()
    {
        $discountedPrice = $this->price - ($this->price * $this->percent);
        return number_format($discountedPrice, 2, ',', '') . ' zł';
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withProductId($product_id) {
        $this->product_id = $product_id;
        return $this;
    }

    public function withRareRate($rare_rate) {
        $this->rare_rate = $rare_rate;
        return $this;
    }

    public function withPercent($percent) {
        $this->percent = $percent;
        return $this;
    }

    public function withName($name) {
        $this->name = $name;
        return $this;
    }

    public function withPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function withImageName($image_path) {
        $this->image_path = $image_path;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 3
            || intval($criteria[0]) == 0
            || !RareRateEnum::isValid($criteria[1])
            || doubleval($criteria[2]) == 0) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }

        return "INSERT INTO rewardable_products VALUES (?,?,?)";
    }

    protected function getReadQuery(...$criteria)
    {
        return "SELECT rp.id, rp.product_id, rp.rare_rate, rp.percent, p.name, p.price, pi.path image_name
                FROM `rewardable_products` rp
                JOIN product p ON p.id = rp.product_id
                LEFT JOIN product_image pi ON pi.product_id = rp.product_id AND pi.is_default = 1";
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

    public function fromResult($result)
    {
        $objects = [];

        while ($row = $result->fetch_assoc()) {
            $objects[] = (new ProductRewardable())
                ->withId($row[ConstUtils::FIELD_LABEL_ID])
                ->withProductId($row[ConstUtils::FIELD_LABEL_PRODUCT_ID])
                ->withName($row[ConstUtils::FIELD_LABEL_NAME])
                ->withPrice($row[ConstUtils::FIELD_LABEL_PRICE])
                ->withPercent($row[ConstUtils::FIELD_LABEL_PERCENT])
                ->withImageName(is_null($row[ConstUtils::FIELD_LABEL_IMAGE_NAME]) ? 'default.png' : $row[ConstUtils::FIELD_LABEL_IMAGE_NAME])
                ->withRareRate(RareRateEnum::from($row[ConstUtils::FIELD_LABEL_RATE_RATE]));
        }

        return empty($objects) ? null : $objects;
    }

    public function getTableName()
    {
        return "rewardable_products";
    }
}