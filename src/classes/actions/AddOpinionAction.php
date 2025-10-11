<?php
include_once '../utils/ConstUtils.php';
if ($_SERVER['REQUEST_METHOD'] === ConstUtils::POST_METHOD) {
    include_once '../abstracts/Entity.php';
    include_once '../../classes/exceptions/NoSuchUserException.php';
    include_once '../enums/CrudEnum.php';
    include_once '../../classes/exceptions/PasswordMismatchException.php';
    include_once '../../classes/entities/User.php';
    include_once '../../classes/entities/Product.php';
    include_once '../../classes/entities/Opinion.php';
    include_once '../../classes/handlers/DatabaseHandler.php';
    include_once '../../classes/Services/LoginService.php';
    include_once '../../classes/Services/ProductService.php';
    require_once __DIR__ . '/../../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
    $dotenv->load();
    session_start();

    $productId = $_POST[ConstUtils::FIELD_LABEL_PRODUCT_ID];
    $stars = $_POST[ConstUtils::FIELD_LABEL_STARS];
    $comment = $_POST[ConstUtils::FIELD_LABEL_COMMENT];
    $user = $_SESSION[ConstUtils::SESSION_USER] ?? null;
    $status = '';

    if (!is_null($user)) {
        ProductService::addOpinionToProduct($productId, $stars, $comment);
        $status = ConstUtils::STATUS_SUCCESS;
    } else {
        $status = ConstUtils::STATUS_ERROR_NO_USER;
    }

    $url = $_SESSION[ConstUtils::PREV_PAGE];
    if ($status !== ConstUtils::STATUS_SUCCESS) {
        $url .= "?error=" . $status;
    }
    header("Location: $url");
}