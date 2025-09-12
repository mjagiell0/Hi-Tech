<?php

class ProductService
{
    private static function dataRetriever(Entity $entity, ...$criteria)
    {
        $records = DatabaseHandler::getDbHandler()
            ->query($entity, CrudEnum::READ, ...$criteria);

        foreach ($records as $record) {
            $record->prepareToDisplay();
        }

        return $records;
    }

    public static function getCategories($section_id)
    {
        return self::dataRetriever(new Category(), $section_id);
    }

    public static function getSections()
    {
        return self::dataRetriever(new Section());
    }

    public static function getProductsWithDiscounts()
    {
        return self::dataRetriever(new ProductDiscount());
    }

    public static function getBestOpinions()
    {
        return self::dataRetriever(new Opinion());
    }
}