<?php

class ProductDetail extends Product
{
    private $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function withDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    protected function getReadQuery(...$criteria)
    {
        if (count($criteria) != 1
            || intval($criteria[0]) === 0) {
            throw new InvalidArgumentException("Insufficient criteria for READ operation.");
        }


        return "SELECT p.id, p.name, p.description, p.stock_quantity,p.producent, p.price, p.archived,
                COALESCE(ROUND(AVG(o.stars), 1), 0) average_rating, COUNT(o.id) opinion_count, c.id AS category_id,
                c.name category_name, s.id AS section_id, s.name section_name, COALESCE(d.percent, 0) discount
                FROM product p
                LEFT JOIN discount d ON d.product_id = p.id
                LEFT JOIN opinion o ON o.product_id = p.id
                JOIN category c ON p.category_id = c.id
                JOIN section s ON p.section_id = s.id
                WHERE p.id = ?
                GROUP BY p.id;";
    }

    protected function fromRow($row)
    {
        return (new ProductDetail())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withProductName($row[ConstUtils::FIELD_LABEL_NAME])
            ->withProducent($row[ConstUtils::FIELD_LABEL_PRODUCENT])
            ->withIsArchived($row[ConstUtils::FIELD_LABEL_ARCHIVED])
            ->withPrice($row[ConstUtils::FIELD_LABEL_PRICE])
            ->withCategoryId($row[ConstUtils::FIELD_LABEL_CATEGORY_ID])
            ->withCategoryName($row[ConstUtils::FIELD_LABEL_CATEGORY_NAME])
            ->withSectorId($row[ConstUtils::FIELD_LABEL_SECTION_ID])
            ->withSectorName($row[ConstUtils::FIELD_LABEL_SECTION_NAME])
            ->withAvgOpinion($row[ConstUtils::FIELD_LABEL_AVG_OPINION])
            ->withOpinionCount($row[ConstUtils::FIELD_LABEL_OPINION_COUNT])
            ->withDiscount($row[ConstUtils::FIELD_LABEL_DISCOUNT])
            ->withStockQuantity($row[ConstUtils::FIELD_LABEL_STOCK_QUANTITY])
            ->withDescription($row[ConstUtils::FIELD_LABEL_DESCRIPTION]);
    }
}