<?php
include_once '../utils/ConstUtils.php';
if ($_SERVER['REQUEST_METHOD'] === ConstUtils::POST_METHOD) {
    include_once '../abstracts/Entity.php';
    include_once '../../classes/exceptions/NoSuchUserException.php';
    include_once '../enums/CrudEnum.php';
    include_once '../../classes/exceptions/PasswordMismatchException.php';
    include_once '../../classes/entities/User.php';
    include_once '../../classes/entities/Product.php';
    include_once '../../classes/handlers/DatabaseHandler.php';
    include_once '../../classes/Services/LoginService.php';
    include_once '../../classes/Services/ProductService.php';
    require_once __DIR__ . '/../../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
    $dotenv->load();
    session_start();

    $productId = $_POST[ConstUtils::FIELD_LABEL_PRODUCT_ID];
    $quantity = $_POST[ConstUtils::FIELD_LABEL_QUANTITY];

    $status = '';

    if (isset($_SESSION[ConstUtils::SESSION_USER])) {
        $user = $_SESSION[ConstUtils::SESSION_USER];
        try {
            ProductService::addProductToCart($productId, $quantity);
            $status = ConstUtils::STATUS_SUCCESS;
        } catch (NoSuchUserException $e) {
            $status = ConstUtils::STATUS_ERROR_NO_USER;
        }
    } else {
        $cart = $_SESSION[ConstUtils::SESSION_USER_CART] ?? [];
        if (isset($cart[$productId])) {
            $currQuantity = $cart[$productId]->getQuantity();
            $currQuantity += $quantity;
            $cart[$productId]->withQuantity(min($currQuantity, $cart[$productId]->getStockQuantity()));
        } else {
            $price = $_POST[ConstUtils::FIELD_LABEL_PRICE];
            $productName = $_POST[ConstUtils::FIELD_LABEL_NAME];
            $imageName = $_POST[ConstUtils::FIELD_LABEL_IMAGE_NAME];
            echo $imageName;
            $producent = $_POST[ConstUtils::FIELD_LABEL_PRODUCENT];
            $discount = $_POST[ConstUtils::FIELD_LABEL_DISCOUNT];
            $stockQuantity = $_POST[ConstUtils::FIELD_LABEL_STOCK_QUANTITY];

            $cart[$productId] = (new CartProduct())
                ->withId($productId)
                ->withQuantity($quantity)
                ->withPrice($price)
                ->withProducent($producent)
                ->withDiscount($discount)
                ->withImageName($imageName)
                ->withProductName($productName)
                ->withStockQuantity($stockQuantity);
            var_dump($cart[$productId]);
        }

        $_SESSION[ConstUtils::SESSION_USER_CART] = $cart;
        $status = ConstUtils::STATUS_SUCCESS;
    }

    http_response_code($status === ConstUtils::STATUS_SUCCESS ? 200 : 400);
    exit;
}