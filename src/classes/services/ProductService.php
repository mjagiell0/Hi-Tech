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

    public static function getCategories($sectionId)
    {
        return self::dataRetriever(new Category(), $sectionId);
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

    public static function getRewardableProducts() {
        return self::dataRetriever(new ProductRewardable());
    }

    public static function getCategoryProducts($categoryId, $page)
    {
        $product = new Product();
        $product->setPage($page);
        return self::dataRetriever($product, $categoryId);
    }

    public static function addProductToCart($productId, $quantity) {
        if (!isset($_SESSION[ConstUtils::SESSION_USER])) {
            throw new NoSuchUserException();
        }
        $user = $_SESSION[ConstUtils::SESSION_USER];

        $dbHandler = DatabaseHandler::getDBHandler();
        $cart = $dbHandler->query(new Cart(), CrudEnum::READ, $user->getId());

        $dbHandler->query(new CartProduct(), CrudEnum::CREATE, $productId, $quantity, $cart->getId());
    }
}