<?php

class ProductSearch extends Product implements JsonSerializable {
    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1
            || strlen($criteria[0]) < 1) {
            throw new InvalidArgumentException("Insufficient criteria for READ operation.");
        }

        return "SELECT p.id, p.name, p.description, p.stock_quantity,p.producent, p.price, p.archived, pi.path image_name, 
                c.id AS category_id,
                c.name category_name, s.id AS section_id, s.name section_name, COALESCE(d.percent, 0) discount
                FROM product p
                LEFT JOIN discount d ON d.product_id = p.id
                LEFT JOIN opinion o ON o.product_id = p.id
                JOIN category c ON p.category_id = c.id
                JOIN section s ON p.section_id = s.id
                LEFT JOIN product_image pi ON pi.product_id = p.id AND pi.is_default = 1
                WHERE p.name LIKE ?
                LIMIT 20";
    }

    public function fromRow($row){
        return (new ProductSearch())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withProductName($row[ConstUtils::FIELD_LABEL_NAME])
            ->withProducent($row[ConstUtils::FIELD_LABEL_PRODUCENT])
            ->withIsArchived($row[ConstUtils::FIELD_LABEL_ARCHIVED])
            ->withPrice($row[ConstUtils::FIELD_LABEL_PRICE])
            ->withImageName(is_null($row[ConstUtils::FIELD_LABEL_IMAGE_NAME]) ? ConstUtils::DEFAULT_IMAGE : $row[ConstUtils::FIELD_LABEL_IMAGE_NAME])
            ->withCategoryId($row[ConstUtils::FIELD_LABEL_CATEGORY_ID])
            ->withCategoryName($row[ConstUtils::FIELD_LABEL_CATEGORY_NAME])
            ->withSectorId($row[ConstUtils::FIELD_LABEL_SECTION_ID])
            ->withSectorName($row[ConstUtils::FIELD_LABEL_SECTION_NAME])
            ->withDiscount($row[ConstUtils::FIELD_LABEL_DISCOUNT])
            ->withStockQuantity($row[ConstUtils::FIELD_LABEL_STOCK_QUANTITY]);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getProductName(),
            'producent' => $this->getProducent(),
            'price' => $this->getPriceValue(),
            'priceFormatted' => $this->getPrice(),
            'priceWithDiscount' => $this->getPriceWithDiscountValue(),
            'priceWithDiscountFormatted' => $this->getPriceWithDiscount(),
            'imageName' => $this->getImageName(),
            'categoryId' => $this->getCategoryId(),
            'categoryName' => $this->getCategoryName(),
            'sectionId' => $this->getSectionId(),
            'sectionName' => $this->getSectionName(),
            'opinionCount' => $this->getOpinionCount(),
            'discount' => $this->getDiscount(),
            'stockQuantity' => $this->getStockQuantity(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}