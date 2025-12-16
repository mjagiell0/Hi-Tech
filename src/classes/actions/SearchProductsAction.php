<?php

include_once '../../classes/utils/ConstUtils.php';

if ($_SERVER[ConstUtils::REQUEST_METHOD] == ConstUtils::GET_METHOD) {
    include_once '../abstracts/Entity.php';
    include_once '../../classes/exceptions/NoSuchUserException.php';
    include_once '../enums/CrudEnum.php';
    include_once '../../classes/exceptions/PasswordMismatchException.php';
    include_once '../../classes/entities/User.php';
    include_once '../../classes/entities/Product.php';
    include_once '../../classes/entities/ProductSearch.php';
    include_once '../../classes/entities/UserSpinReward.php';
    include_once '../../classes/handlers/DatabaseHandler.php';
    include_once '../../classes/Services/LoginService.php';
    include_once '../../classes/Services/ProductService.php';

    require_once __DIR__ . '/../../../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
    $dotenv->load();

    $input = $_GET[ConstUtils::GET_PARAMETER_INPUT];

    if (strlen($input) > 0) {
        header('Content-Type: application/json');
        echo json_encode(ProductService::searchProducts($input.'%'));
        exit;
    }

    echo json_encode([]);
    exit;

}