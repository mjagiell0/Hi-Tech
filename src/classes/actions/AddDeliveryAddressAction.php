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
    $city = $_POST[ConstUtils::FIELD_LABEL_CITY];
    $street = $_POST[ConstUtils::FIELD_LABEL_STREET];
    $houseNumber = $_POST[ConstUtils::FIELD_LABEL_HOUSE_NUMBER];
    $postalCode = $_POST[ConstUtils::FIELD_LABEL_POSTAL_CODE];
    $doSave = $_POST[ConstUtils::FLAG_SAVE];
    $user = $_SESSION[ConstUtils::SESSION_USER];

    $status = ConstUtils::STATUS_SUCCESS;

    $address = (new Address())
        ->withCity($city)
        ->withPostalCode($postalCode)
        ->withStreet($street)
        ->withHouseNumber($houseNumber);

    if ($doSave) {
        $address->withOwnerId($user->getId());
        try {
            DatabaseHandler::getDbHandler()->query(
                $address,
                CrudEnum::CREATE,
                $user->getId(),
                $city,
                $street,
                $postalCode,
                $houseNumber
            );
            $_SESSION[ConstUtils::ORDER_ADDRESS] = $address;
        } catch (InvalidArgumentException $e) {
            $status = ConstUtils::STATUS_ERROR.'&message='.$e->getMessage();
        }
    }

    $nextPage = $_SESSION[ConstUtils::PREV_PAGE];
    if ($status !== ConstUtils::STATUS_SUCCESS) {
        $nextPage .= $status;
    }

    header("Location: ../../pages/order-summary/order-summary-payment-method-select.php?status=". $status);
}