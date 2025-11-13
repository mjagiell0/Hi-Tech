<?php
include_once '../utils/ConstUtils.php';
if ($_SERVER['REQUEST_METHOD'] === ConstUtils::POST_METHOD) {
    include_once '../abstracts/Entity.php';
    include_once '../../classes/exceptions/NoSuchUserException.php';
    include_once '../enums/CrudEnum.php';
    include_once '../../classes/exceptions/PasswordMismatchException.php';
    include_once '../../classes/entities/User.php';
    include_once '../../classes/entities/PaymentCard.php';
    include_once '../../classes/handlers/DatabaseHandler.php';
    include_once '../../classes/Services/LoginService.php';
    include_once '../../classes/Services/ProductService.php';
    require_once __DIR__ . '/../../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
    $dotenv->load();
    session_start();

    $status = '';
    $cardholderName = $_POST[ConstUtils::FIELD_LABEL_CARDHOLDER_FIRSTNAME].' '.$_POST[ConstUtils::FIELD_LABEL_CARDHOLDER_LASTNAME];
    $cardLast4Number = substr($_POST[ConstUtils::FIELD_LABEL_CARD_NUMBER], 0, 4);
    $cardExpirationDate = explode('-', $_POST[ConstUtils::FIELD_LABEL_EXPIRATION_DATE]);
    $cardExpirationMonth = $cardExpirationDate[1];
    $cardExpirationYear = $cardExpirationDate[0];
    $cardCvv = $_POST[ConstUtils::FIELD_LABEL_CVV];
    $doSave = $_POST[ConstUtils::FLAG_SAVE];
    $user = $_SESSION[ConstUtils::SESSION_USER];

    $status = ConstUtils::STATUS_SUCCESS;

    $card = (new PaymentCard())
        ->withCardholderName($cardholderName)
        ->withCardNumberLast4($cardLast4Number)
        ->withExpirationMonth($cardExpirationMonth)
        ->withExpirationYear($cardExpirationYear)
        ->withCvv($cardCvv);

    if ($doSave) {
        $card->withUserId($user->getId());
        try {
            DatabaseHandler::getDbHandler()->query(
                $card,
                CrudEnum::CREATE,
                $user->getId(),
                $cardholderName,
                $cardLast4Number,
                $cardExpirationMonth,
                $cardExpirationYear,
                $cardCvv
            );

            $_SESSION[ConstUtils::ORDER_PAYMENT_CARD] = DatabaseHandler::getDbHandler()->query(
                $card,
                CrudEnum::READ,
                $user->getId()
            );
        } catch (InvalidArgumentException $e) {
            $status = ConstUtils::STATUS_ERROR.'&message='.$e->getMessage();
        }
    }

    header("Location: ../../pages/order-summary/order-summary-finish.php?status=". $status);
}