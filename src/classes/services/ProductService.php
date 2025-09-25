<?php

if (!class_exists('CartProduct')) {
    include_once '../../classes/entities/CartProduct.php';
}

class ProductService
{
    private static function dataRetriever(Entity $entity, ...$criteria): array
    {
        $records = DatabaseHandler::getDbHandler()
            ->query($entity, CrudEnum::READ, ...$criteria) ?? [];

        foreach ($records as $record) {
            $record->prepareToDisplay();
        }

        return (is_array($records) ? $records : [$records]) ?? [];
    }

    public static function getCategories($sectionId): array
    {
        return self::dataRetriever(new Category(), $sectionId);
    }

    public static function getSections():array
    {
        return self::dataRetriever(new Section());
    }

    public static function getProductsWithDiscounts():array
    {
        return self::dataRetriever(new ProductDiscount());
    }

    public static function getBestOpinions(): array
    {
        return self::dataRetriever(new Opinion());
    }

    public static function getRewardableProducts(): array
    {
        return self::dataRetriever(new ProductRewardable());
    }

    public static function getCartProducts(): array
    {
        $user = $_SESSION[ConstUtils::SESSION_USER];

        return self::dataRetriever(new CartProduct(), $user->getId());
    }

    public static function getCategoryProducts($categoryId, $page): array
    {
        $product = new Product();
        $product->setPage($page);
        return self::dataRetriever($product, $categoryId);
    }

    public static function addProductToCart($productId, $quantity): void
    {
        if (!isset($_SESSION[ConstUtils::SESSION_USER])) {
            throw new NoSuchUserException();
        }
        $user = $_SESSION[ConstUtils::SESSION_USER];

        $dbHandler = DatabaseHandler::getDBHandler();
        $cartItem = $dbHandler->query(new CartProduct(), CrudEnum::READ, $productId, $user->getId());
        $crudType = CrudEnum::CREATE;

        if (!is_null($cartItem)) {
            $quantity = min($quantity + $cartItem->getQuantity(), $cartItem->getStockQuantity());
            $crudType = CrudEnum::UPDATE;
        }

        $dbHandler->query(new CartProduct(), $crudType, $quantity, $productId, $user->getId());
    }

    public static function mergeCartWithAccount(): void
    {
        $sessionCart =& $_SESSION[ConstUtils::SESSION_USER_CART];
        $productIds = array_keys($sessionCart ?? []);

        if (!(empty($productIds))) {
            $user = $_SESSION[ConstUtils::SESSION_USER];
            $dbHandler = DatabaseHandler::getDBHandler();
            $userCart = $dbHandler->query(new CartProduct(), CrudEnum::READ, implode(',', $productIds), $user->getId()) ?? [];
            if (!is_array($userCart)) {
                $userCart = [$userCart];
            }
            $dbHandler->beginTransaction();
            try {
                foreach ($userCart as $userCartItem) {
                    $sessionCartItem = $sessionCart[$userCartItem->getId()];
                    $quantity = $sessionCartItem->getQuantity() + $userCartItem->getQuantity();
                    $userCartItem->withQuantity(min($userCartItem->getStockQuantity(), $quantity));
                    $dbHandler->query(new CartProduct(), CrudEnum::UPDATE, $userCartItem->getQuantity(), $userCartItem->getId(), $user->getId());
                    unset($sessionCart[$userCartItem->getId()]);
                }
                foreach ($sessionCart as $sessionCartItem) {
                    $dbHandler->query(new CartProduct(), CrudEnum::CREATE, $sessionCartItem->getQuantity(), $sessionCartItem->getId(), $user->getId());
                }

                $dbHandler->commit();
            } catch (Exception) {
                $dbHandler->rollback();
            }
            
        }
    }

    public static function removeFromCart($productId): void
    {
        $dbHandler = DatabaseHandler::getDbHandler();
        $user = $_SESSION[ConstUtils::SESSION_USER];
        $dbHandler->query(new CartProduct(), CrudEnum::DELETE, $productId, $user->getId());
    }

    public static function updateCartItemQuantity($productId, $quantity): void
    {
        $dbHandler = DatabaseHandler::getDbHandler();
        $user = $_SESSION[ConstUtils::SESSION_USER];

        $dbHandler->query(new CartProduct(), CrudEnum::UPDATE, $quantity, $productId, $user->getId());
    }
}