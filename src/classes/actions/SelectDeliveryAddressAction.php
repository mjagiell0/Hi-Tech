<?php
include_once '../utils/ConstUtils.php';
if ($_SERVER['REQUEST_METHOD'] === ConstUtils::POST_METHOD) {
    include_once '../abstracts/Entity.php';
    include_once '../../classes/exceptions/NoSuchUserException.php';
    include_once '../enums/CrudEnum.php';
    include_once '../../classes/exceptions/PasswordMismatchException.php';
    include_once '../../classes/entities/User.php';
    include_once '../../classes/entities/Address.php';
    include_once '../../classes/handlers/DatabaseHandler.php';
    include_once '../../classes/Services/LoginService.php';
    include_once '../../classes/Services/ProductService.php';
    require_once __DIR__ . '/../../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
    $dotenv->load();
    session_start();

    $status = '';
    $addressId = $_POST[ConstUtils::FIELD_LABEL_ADDRESS_ID];
    $user = $_SESSION[ConstUtils::SESSION_USER];

    $status = ConstUtils::STATUS_SUCCESS;

    try {
        $_SESSION[ConstUtils::ORDER_ADDRESS] = DatabaseHandler::getDbHandler()
        ->query(new Address(), CrudEnum::READ, $user->getId(), $addressId);
    } catch (InvalidArgumentException $e) {
        $status = ConstUtils::STATUS_ERROR.'&message='.$e->getMessage();
    }

    header("Location: ../../pages/order-summary/order-summary-payment-method-select.php?status=". $status);
}