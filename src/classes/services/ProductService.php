<?php

class ProductService
{
    public static function getCategories($section_id)
    {
        $dbHandler = DatabaseHandler::getDbHandler();

        $categories = $dbHandler->query(new Category(), CrudEnum::READ, $section_id);

        foreach ($categories as $category) {
            $category->setName(htmlspecialchars($category->getName(), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        return $categories;
    }

    public static function getSections()
    {
        return DatabaseHandler::getDbHandler()->query(new Section(), CrudEnum::READ);
    }

    public static function getProductsWithDiscounts()
    {
        $dbHandler = DatabaseHandler::getDbHandler();

        $products = $dbHandler->query(new ProductDiscount(), CrudEnum::READ);
        foreach ($products as $product) {
            $product->withName(htmlspecialchars($product->getName(), ENT_QUOTES | ENT_HTML5, 'UTF-8'))
                ->withImageName(htmlspecialchars($product->getImageName(), ENT_QUOTES | ENT_HTML5, 'UTF-8'))
                ->withProducent(htmlspecialchars($product->getProducent(), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        return $products;
    }
}