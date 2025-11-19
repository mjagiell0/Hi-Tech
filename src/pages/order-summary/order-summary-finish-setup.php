<?php
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/enums/CrudEnum.php';
include_once '../../classes/handlers/DatabaseHandler.php';
include_once '../../classes/abstracts/Entity.php';
include_once '../../classes/services/LoginService.php';
include_once '../../classes/services/ProductService.php';
include_once '../../classes/entities/User.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

session_start();

$user = $_SESSION[ConstUtils::SESSION_USER] ?? null;
if (is_null($user)) {
    header('location: ../login/login.php');
}

$cart = ProductService::getCartProducts();
$cartSum = ProductService::sumCartProductsPrice($cart);
$address = $_SESSION[ConstUtils::ORDER_ADDRESS];
$paymentCard = $_SESSION[ConstUtils::ORDER_PAYMENT_CARD];