<?php

class ProductFortune extends Entity
{
    private $id;
    private $productId;
    private $name;
    private $imagePath;
    private $price;
    private $discount;
    private $createdAt;
    private $expiresAt;

    public function getId() {
        return $this->id;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getName() {
        return $this->name;
    }

    public function getImagePath() {
        return $this->imagePath;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getExpiresAt() {
        return $this->expiresAt;
    }

    public function withId($id) {
        $this->id = $id;
        return $this;
    }

    public function withProductId($productId) {
        $this->productId = $productId;
        return $this;
    }

    public function withName($name) {
        $this->name = $name;
        return $this;
    }

    public function withImagePath($imagePath) {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function withPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function withDiscount($discount) {
        $this->discount = $discount;
        return $this;
    }

    public function withCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function withExpiresAt($expiresAt) {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 4
            || intval($criteria[0]) == 0
            || intval($criteria[1]) == 0
            || !is_string($criteria[2])
            || !is_string($criteria[3])) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }

        return "INSERT INTO user_spin_rewards(user_id, reward_id, created_at, expires_at) VALUES (?,?,?,?)";
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }

        return "SELECT sr.id ,rp.product_id, p.name, pi.path image_name, p.price, rp.percent discount, sr.created_at, sr.expires_at  
                FROM user_spin_rewards sr 
                JOIN rewardable_products rp ON sr.reward_id = rp.id
                JOIN product p ON p.id = rp.product_id
                LEFT JOIN product_image pi ON rp.product_id = pi.product_id AND pi.is_default = 1
                WHERE sr.user_id = ?";
    }

    protected function getUpdateQuery(...$criteria)
    {
        // TODO: Implement getUpdateQuery() method.
    }

    protected function getDeleteQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) == 0) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }

        return "DELETE FROM user_spin_rewards WHERE user_id = ?";
    }

    public function prepareToDisplay()
    {
        $this->name = htmlspecialchars($this->getName());
        $this->price = htmlspecialchars($this->getPrice());
        $this->discount = htmlspecialchars($this->getDiscount());
        $this->createdAt = htmlspecialchars($this->getCreatedAt());
        $this->expiresAt = htmlspecialchars($this->getExpiresAt());
    }

    public function fromRow($row)
    {
        return (new ProductFortune())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withProductId($row[ConstUtils::FIELD_LABEL_PRODUCT_ID])
            ->withName($row[ConstUtils::FIELD_LABEL_NAME])
            ->withPrice($row[ConstUtils::FIELD_LABEL_PRICE])
            ->withDiscount($row[ConstUtils::FIELD_LABEL_DISCOUNT])
            ->withCreatedAt($row[ConstUtils::FIELD_LABEL_CREATED_AT])
            ->withExpiresAt($row[ConstUtils::FIELD_LABEL_EXPIRES_AT])
            ->withImagePath($row[ConstUtils::FIELD_LABEL_IMAGE_NAME]);
    }

    public function getTableName()
    {
        return 'user_spin_rewards';
    }
}