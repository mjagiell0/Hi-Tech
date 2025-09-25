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

    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION[ConstUtils::SESSION_USER])) {
        ProductService::updateCartItemQuantity($productId, $quantity);
    } else {
        $_SESSION[ConstUtils::SESSION_USER_CART][$productId]->withQuantity($quantity);
    }
    http_response_code(200);
}