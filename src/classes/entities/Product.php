<?php

class Product extends Entity
{
    private $id;
    private $productName;
    private $producent;
    private $isArchived;
    private $price;
    private $imageName;
    private $categoryId;
    private $categoryName;
    private $sectorId;
    private $sectorName;
    private $page;
    private $avgOpinion;
    private $opinionCount;
    private $discount;
    private $stockQuantity;

    public function getId()
    {
        return $this->id;
    }

    public function getProductName()
    {
        return $this->productName;
    }

    public function getProducent()
    {
        return $this->producent;
    }

    public function getPrice()
    {
        return number_format($this->price, 2, ',', ' ') . ' zł';
    }

    public function getPriceWithDiscount()
    {
        $discountedPrice = $this->price - ($this->price * $this->discount);
        return number_format($discountedPrice, 2, ',', ' ') . ' zł';
    }

    public function isArchived()
    {
        return $this->isArchived;
    }

    public function getImageName()
    {
        return $this->imageName;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getCategoryName()
    {
        return $this->categoryName;
    }

    public function getSectionId()
    {
        return $this->sectorId;
    }

    public function getSectionName()
    {
        return $this->sectorName;
    }

    public function getAvgOpinion()
    {
        return $this->avgOpinion;
    }

    public function getOpinionCount()
    {
        return $this->opinionCount;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function getStockQuantity()
    {
        return $this->stockQuantity;
    }

    public function getPriceValue()
    {
        return $this->price;
    }

    public function getPriceWithDiscountValue()
    {
        return $this->price - ($this->price * $this->discount);
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withProductName($productName)
    {
        $this->productName = $productName;
        return $this;
    }

    public function withProducent($producent)
    {
        $this->producent = $producent;
        return $this;
    }

    public function withIsArchived($isArchived)
    {
        $this->isArchived = $isArchived;
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

    public function withCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function withCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
        return $this;
    }

    public function withSectorId($sectorId)
    {
        $this->sectorId = $sectorId;
        return $this;
    }

    public function withSectorName($sectorName)
    {
        $this->sectorName = $sectorName;
        return $this;
    }

    public function withAvgOpinion($avgOpinion)
    {
        $this->avgOpinion = $avgOpinion;
        return $this;
    }

    public function withOpinionCount($opinionCount)
    {
        $this->opinionCount = $opinionCount;
        return $this;
    }

    public function withDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    public function withStockQuantity($quantity)
    {
        $this->stockQuantity = $quantity;
        return $this;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    protected function getCreateQuery(...$criteria)
    {
        // TODO: Implement getCreateQuery() method.
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for READ operation.");
        }

        $limit = ConstUtils::RECORD_PER_PAGE;
        $offset = ($this->page - 1) * $limit;


        return "SELECT p.id, p.name, p.description, p.stock_quantity,p.producent, p.price, p.archived, pi.path image_name, 
                COALESCE(ROUND(AVG(o.stars), 1), 0) average_rating, COUNT(o.id) opinion_count, c.id AS category_id,
                c.name category_name, s.id AS section_id, s.name section_name, COALESCE(d.percent, 0) discount
                FROM product p
                LEFT JOIN discount d ON d.product_id = p.id
                LEFT JOIN opinion o ON o.product_id = p.id
                JOIN category c ON p.category_id = c.id
                JOIN section s ON p.section_id = s.id
                LEFT JOIN product_image pi ON pi.product_id = p.id AND pi.is_default = 1
                WHERE p.category_id = ?
                GROUP BY p.id
                LIMIT $limit OFFSET $offset;";
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
        return (new Product())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withProductName($row[ConstUtils::FIELD_LABEL_NAME])
            ->withProducent($row[ConstUtils::FIELD_LABEL_PRODUCENT])
            ->withIsArchived($row[ConstUtils::FIELD_LABEL_ARCHIVED])
            ->withPrice($row[ConstUtils::FIELD_LABEL_PRICE])
            ->withImageName(is_null($row[ConstUtils::FIELD_LABEL_IMAGE_NAME]) ? 'default.png' : $row[ConstUtils::FIELD_LABEL_IMAGE_NAME])
            ->withCategoryId($row[ConstUtils::FIELD_LABEL_CATEGORY_ID])
            ->withCategoryName($row[ConstUtils::FIELD_LABEL_CATEGORY_NAME])
            ->withSectorId($row[ConstUtils::FIELD_LABEL_SECTION_ID])
            ->withSectorName($row[ConstUtils::FIELD_LABEL_SECTION_NAME])
            ->withAvgOpinion($row[ConstUtils::FIELD_LABEL_AVG_OPINION])
            ->withOpinionCount($row[ConstUtils::FIELD_LABEL_OPINION_COUNT])
            ->withDiscount($row[ConstUtils::FIELD_LABEL_DISCOUNT])
            ->withStockQuantity($row[ConstUtils::FIELD_LABEL_STOCK_QUANTITY]);
    }

    public function prepareToDisplay()
    {
        $this->productName = htmlspecialchars($this->productName);
        $this->producent = htmlspecialchars($this->producent);
        $this->categoryName = htmlspecialchars($this->categoryName);
        $this->sectorName = htmlspecialchars($this->sectorName);
        $this->price = htmlspecialchars($this->price);
        $this->imageName = htmlspecialchars($this->imageName);
    }

    public function getTableName()
    {
        // TODO: Implement getTableName() method.
    }
}